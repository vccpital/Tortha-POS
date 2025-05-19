<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index() {
        $orderItems = OrderItem::with(['order', 'product'])->get();
        return view('order-items.index', compact('orderItems'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'discount' => 'numeric',
        ]);
        OrderItem::create($validated);
        return redirect()->back()->with('success', 'Order item added.');
    }

    public function update(Request $request, OrderItem $orderItem) {
        $orderItem->update($request->all());
        return redirect()->back()->with('success', 'Order item updated.');
    }

    public function destroy(OrderItem $orderItem) {
        $orderItem->delete();
        return redirect()->back()->with('success', 'Order item deleted.');
    }
}
