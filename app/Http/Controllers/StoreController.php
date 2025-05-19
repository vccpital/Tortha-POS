<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('users')->get();
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_info' => 'nullable|array',
        ]);

        $validated['contact_info'] = json_encode($validated['contact_info']); // store as JSON if needed

        Store::create($validated);

        return redirect()->route('stores.index')->with('success', 'Store created successfully.');
    }

    public function show(Store $store)
    {
        $store->load('users');
        return view('stores.show', compact('store'));
    }

    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_info' => 'nullable|array',
        ]);

        $validated['contact_info'] = json_encode($validated['contact_info']);

        $store->update($validated);

        return redirect()->route('stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('stores.index')->with('success', 'Store deleted successfully.');
    }
}
