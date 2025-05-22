<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_primary' => 'nullable|boolean',
        ]);

        // Upload to S3
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->storePublicly('products', 's3');
            /** @var \Illuminate\Contracts\Filesystem\Cloud $s3 */
            $s3 = Storage::disk('s3');
            $url = $s3->url($path);

            ProductImage::create([
                'product_id' => $validated['product_id'],
                'image_url'  => $url,
                'is_primary' => $request->get('is_primary', false),
            ]);

            return redirect()->back()->with('success', 'Product image uploaded to S3 successfully.');
        }

        return redirect()->back()->with('error', 'No image was uploaded.');
    }

    public function destroy(ProductImage $productImage)
    {
        // Optionally delete from S3
        $url = $productImage->image_url;
        if (str_contains($url, 'amazonaws.com')) {
            $key = ltrim(parse_url($url, PHP_URL_PATH), '/');
            Storage::disk('s3')->delete($key);
        }

        $productImage->delete();

        return redirect()->back()->with('success', 'Product image deleted.');
    }
}
