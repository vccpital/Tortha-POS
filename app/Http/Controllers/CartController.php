<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
public function index()
{
    $order = Order::with('items.product')
        ->where('user_id', Auth::id())
        ->where('status', 'cart')
        ->first();

    if (!$order) {
        // If no order found, you can return a message or an empty cart view
        return view('cart.index', ['order' => null])->with('message', 'Your cart is empty.');
    }

    return view('cart.index', compact('order'));
}


public function addProduct(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($validated['product_id']);

    // Find or create an order in "cart" status
    $order = Order::firstOrCreate(
        [
            'user_id' => Auth::id(),
            'status'  => 'cart',
        ],
        [
            'store_id' => $product->store_id,
            'total'    => 0,
            'payment_status' => 'unpaid',
        ]
    );

    // If the product's store doesn't match the order's store, show an error
    if ($order->store_id != $product->store_id) {
        return redirect()->route('cart.index')->with('error', 'You can only add products from one store at a time.');
    }

    // Add or update order item
    $item = $order->items()->where('product_id', $product->id)->first();

    if ($item) {
        $item->quantity += $validated['quantity'];
        $item->save();
    } else {
        $order->items()->create([
            'product_id' => $product->id,
            'quantity'   => $validated['quantity'],
            'price'      => $product->price,
            'discount'   => 0,
        ]);
    }

    // Recalculate total
    $order->total = $order->items->sum(fn($item) => $item->price * $item->quantity);
    $order->save();

    return redirect()->route('cart.index')->with('success', 'Product added to cart.');
}


public function remove(OrderItem $item)
{
    // Ensure the item is associated with an order
    $order = $item->order;

    if (!$order) {
        // If no order is associated with the item, redirect back with an error
        return redirect()->route('cart.index')->with('error', 'Item is not associated with any order.');
    }

    // Delete the item from the order
    $item->delete();

    // If there are no items left in the cart, you can delete the order or mark it as empty.
    if ($order->items->count() == 0) {
        $order->delete();
        return redirect()->route('cart.index')->with('success', 'Your cart is now empty.');
    }

    // Recalculate total
    $order->total = $order->items->sum(fn($i) => $i->price * $i->quantity);
    $order->save();

    return redirect()->back()->with('success', 'Item removed from cart.');
}


public function checkout(Request $request)
{
    $order = Order::where('user_id', Auth::id())
                  ->where('status', 'cart')
                  ->firstOrFail();

    if ($order->items->count() == 0) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty, cannot checkout.');
    }

    $order->status = 'pending';
    $order->payment_status = 'unpaid';
    $order->save();

    return redirect()->route('orders.show', $order)->with('success', 'Checkout initiated.');
}
}
