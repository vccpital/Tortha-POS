<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create() {
        $stores = Store::all();
        return view('products.create', compact('stores'));
    }

public function index()
{
    $user = Auth::user();

    // If the user is a cashier, only show products for their store
    if ($user->usertype === 'cashier') {
        $products = Product::with('images')
            ->where('store_id', $user->store_id)
            ->get();
    } else {
        // For all other user types, show all products
        $products = Product::with('images')->get();
    }

    return view('products.index', compact('products'));
}

    public function show(Product $product) {
        $product->load('images');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $stores = Store::all();
        $product->load('images');

        return view('products.edit', compact('product', 'stores'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'store_id'    => 'required|exists:stores,id',
        'name'        => 'required|string',
        'sku'         => 'required|string|unique:products',
        'barcode'     => 'required|string|unique:products',
        'category'    => 'nullable|string',
        'price'       => 'required|numeric',
        'stock_qty'   => 'integer',
        'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
    ]);

    logger()->debug('Validated product data:', $validated); // Log validated data

    $product = Product::create($validated);
    logger()->info("Product created with ID: {$product->id}"); // Log product creation at info level

    // Handle image upload
    if ($request->hasFile('image')) {
        logger()->debug('Image file detected in request.'); // Log image file detection

        $file = $request->file('image');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $size = $file->getSize();

        logger()->debug("Image details — Name: {$originalName}, Type: {$mimeType}, Size: {$size} bytes"); // Log image details

        try {
            // Generate unique filename
            $filename = uniqid('product_') . '.' . $file->getClientOriginalExtension();

            // Log the file path before uploading for debugging purposes
            logger()->debug("File path before upload: {$filename}");

            // Upload to S3
            $path = Storage::disk('s3')->putFileAs('products', $file, $filename);
            if (!$path) {
                logger()->error("S3 upload failed — path is empty."); // Log error if upload fails
                throw new \Exception('Image upload failed.');
            }

            logger()->debug("Image uploaded to S3 path: {$path}"); // Log image upload success

            /** @var \Illuminate\Contracts\Filesystem\Cloud $s3 */
            $s3 = Storage::disk('s3');
            $url = $s3->url($path);

            logger()->info("Public S3 URL generated: {$url}"); // Log the generated URL at info level

            ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => $url,
                'is_primary' => true,
            ]);

            logger()->info("ProductImage record created for product ID {$product->id}"); // Log image record creation at info level

        } catch (\Exception $e) {
            logger()->error("Image upload to S3 failed: " . $e->getMessage()); // Log error if exception occurs
        }
    } else {
        logger()->debug('No image found in request.'); // Log if no image found in request
    }

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}

public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'store_id'    => 'required|exists:stores,id',
        'name'        => 'required|string',
        'sku'         => 'required|string|unique:products,sku,' . $product->id,
        'barcode'     => 'required|string|unique:products,barcode,' . $product->id,
        'category'    => 'nullable|string',
        'price'       => 'required|numeric',
        'stock_qty'   => 'integer',
        'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
    ]);

    logger()->debug('Validated product update data:', $validated);

    $product->update($validated);
    logger()->info("Product updated with ID: {$product->id}");

    // Handle new image upload
    if ($request->hasFile('image')) {
        logger()->debug('Image file detected in request.');

        $file = $request->file('image');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $size = $file->getSize();

        logger()->debug("Image details — Name: {$originalName}, Type: {$mimeType}, Size: {$size} bytes");

        try {
            $filename = uniqid('product_') . '.' . $file->getClientOriginalExtension();
            logger()->debug("File path before upload: {$filename}");

            $path = Storage::disk('s3')->putFileAs('products', $file, $filename);
            if (!$path) {
                logger()->error("S3 upload failed — path is empty.");
                throw new \Exception('Image upload failed.');
            }

            logger()->debug("Image uploaded to S3 path: {$path}");
            /** @var \Illuminate\Contracts\Filesystem\Cloud $s3 */
            $s3 = Storage::disk('s3');
            $url = $s3->url($path);
            logger()->info("Public S3 URL generated: {$url}");

            // Delete existing primary image if it exists
            $existingImage = $product->images()->where('is_primary', true)->first();
            if ($existingImage) {
                // Delete from S3 if URL is from S3
                if (str_contains($existingImage->image_url, 'amazonaws.com')) {
                    $existingKey = ltrim(parse_url($existingImage->image_url, PHP_URL_PATH), '/');
                    Storage::disk('s3')->delete($existingKey);
                    logger()->info("Deleted old image from S3: {$existingKey}");
                }

                $existingImage->delete();
                logger()->info("Deleted existing primary ProductImage record.");
            }

            // Create new image record
            ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => $url,
                'is_primary' => true,
            ]);

            logger()->info("New ProductImage record created for product ID {$product->id}");

        } catch (\Exception $e) {
            logger()->error("Image upload to S3 failed: " . $e->getMessage());
        }
    } else {
        logger()->debug('No image found in request.');
    }

    return redirect()->route('products.show', $product)->with('success', 'Product updated successfully.');
}


public function destroy(Product $product)
{
    // Delete all associated images
    foreach ($product->images as $image) {
        if (str_contains($image->image_url, 'amazonaws.com')) {
            $key = ltrim(parse_url($image->image_url, PHP_URL_PATH), '/');
            if (Storage::disk('s3')->exists($key)) {
                Storage::disk('s3')->delete($key);
                logger()->info("Deleted image from S3: {$key}");
            }
        } else {
            if (Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
                logger()->info("Deleted image from local storage: {$image->image_url}");
            }
        }

        $image->delete();
        logger()->info("Deleted ProductImage record with ID: {$image->id}");
    }

    // Delete the product
    $productId = $product->id;
    $product->delete();
    logger()->info("Deleted Product with ID: {$productId}");

    return redirect()->route('products.index')->with('success', 'Product and its images deleted successfully.');
}

}
