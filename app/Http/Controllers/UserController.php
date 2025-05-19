<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create()
    {
        $stores = store::all();
        return view('users.create', compact('stores'));
    }
    // Show users list page with relevant data
    public function index()
    {
        $currentUser = Auth::user();

        if ($currentUser->usertype === 'admin') {
            // Users in the same store as the logged-in admin
            $usersSameStore = User::where('store_id', $currentUser->store_id)
                ->with('store')
                ->get();

            return view('users.index', compact('usersSameStore'));
        }

        if ($currentUser->usertype === 'devadmin') {
            // Group users by store for devadmin
            $usersByStore = User::with('store')
                ->get()
                ->groupBy(fn($user) => $user->store ? $user->store->name : 'No Store');

            return view('users.index', compact('usersByStore'));
        }

        // For other user types, redirect or show message
        return redirect()->route('home')->with('error', 'Unauthorized access');
    }
    
    public function edit(User $user)
    {
        $stores = Store::all();
        return view('users.edit', compact('user', 'stores'));
    }

    // Show single user details page (you can create the view)
    public function show(User $user)
    {
        return view('users.show', ['user' => $user->load('store')]);
    }

    // Store new user and redirect with flash message
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'usertype' => 'required|in:devadmin,admin,cashier,user',
            'phone_number' => 'required|string',
            'status' => 'required|in:active,inactive,suspended',
            'store_id' => 'nullable|exists:stores,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Update user with validation and redirect
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'usertype' => 'required|in:devadmin,admin,cashier,user',
            'phone_number' => 'required|string',
            'status' => 'required|in:active,inactive,suspended',
            'store_id' => 'nullable|exists:stores,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']); // keep existing password if none provided
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete user and redirect with flash message
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
