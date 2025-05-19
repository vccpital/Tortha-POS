<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create() {
        $stores = Store::all();
        return view('products.create', compact('stores'));
    }

    public function index() {
        $products = Product::with('images')->get();
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
        'store_id' => 'required|exists:stores,id',
        'name' => 'required|string',
        'sku' => 'required|string|unique:products',
        'barcode' => 'required|string|unique:products',
        'category' => 'nullable|string',
        'price' => 'required|numeric',
        'stock_qty' => 'integer',
        'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
    ]);

    // Create the product
    $product = Product::create($validated);

    // Handle image upload
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $path = $file->store('images/products', 'public');

        ProductImage::create([
            'product_id' => $product->id,
            'image_url' => $path,
            'is_primary' => true,
        ]);
    }

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}


    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'store_id'  => 'required|exists:stores,id',
            'name'      => 'required|string',
            'sku'       => 'required|string|unique:products,sku,' . $product->id,
            'barcode'   => 'required|string|unique:products,barcode,' . $product->id,
            'category'  => 'nullable|string',
            'price'     => 'required|numeric',
            'stock_qty' => 'nullable|integer',
            'image'     => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $product->update($validated);

        if ($request->hasFile('image')) {
            $oldImage = $product->images()->where('is_primary', true)->first();

            if ($oldImage) {
                Storage::disk('public')->delete($oldImage->image_url);
                $oldImage->delete();
            }

            $path = $request->file('image')->store('images/products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => $path,
                'is_primary' => true,
            ]);
        }

        return redirect()->route('products.show', $product)->with('success', 'Product updated successfully.');
    }

public function destroy(Product $product)
{
    foreach ($product->images as $image) {
        Storage::disk('public')->delete($image->image_url);
        $image->delete();
    }

    $product->delete();
    return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
}

}
