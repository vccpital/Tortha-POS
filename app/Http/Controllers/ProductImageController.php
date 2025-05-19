<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_url' => 'required|string',
            'is_primary' => 'boolean',
        ]);
        ProductImage::create($validated);
        return redirect()->back()->with('success', 'Product image added.');
    }

    public function destroy(ProductImage $productImage) {
        $productImage->delete();
        return redirect()->back()->with('success', 'Product image deleted.');
    }
}
