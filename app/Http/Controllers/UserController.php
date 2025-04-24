<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users based on type.
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');
        
        $query = User::query();
        
        // Filter by user type
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('name')->paginate(15);
        
        return view('users.index', compact('users', 'type'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Password::defaults()],
            'type' => 'required|in:customer,admin,delivery',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
        ]);
        
        // Set default values for checkboxes
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // Hash the password
        $validated['password'] = Hash::make($validated['password']);
        
        // Handle image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $imagePath;
        }
        
        User::create($validated);
        
        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Load related data based on user type
        if ($user->isCustomer()) {
            $user->load('orders', 'ratings');
        } elseif ($user->isDelivery()) {
            $user->load('deliveryOrders');
        }
        
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::defaults()],
            'type' => 'required|in:customer,admin,delivery',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
        ]);
        
        // Set default values for checkboxes
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // Update password only if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        // Handle image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $imagePath;
        }
        
        $user->update($validated);
        
        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Check if user has orders
        if ($user->orders()->count() > 0) {
            return back()->with('error', 'Cannot delete user with orders.');
        }
        
        // Check if user is the only admin
        if ($user->isAdmin() && User::where('type', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the only admin user.');
        }
        
        // Delete profile image
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }
        
        // Delete related data
        $user->ratings()->delete();
        $user->carts()->delete();
        
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
