<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Product;
use Illuminate\Http\Request;

class RateController extends Controller
{
    /**
     * Display a listing of the user's ratings.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin sees all ratings
            $ratings = Rate::with(['user', 'product'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // User sees their own ratings
            $ratings = $user->ratings()
                ->with('product')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        return view('ratings.index', compact('ratings'));
    }

    /**
     * Show the form for creating a new rating.
     */
    public function create(Request $request)
    {
        $productId = $request->query('product_id');
        
        if (!$productId) {
            return redirect()->route('products.index')
                ->with('error', 'Product not specified.');
        }
        
        $product = Product::findOrFail($productId);
        
        // Check if user already rated this product
        $user = auth()->user();
        $existingRating = Rate::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
            
        if ($existingRating) {
            return redirect()->route('rates.edit', $existingRating)
                ->with('info', 'You already rated this product. You can update your rating.');
        }
        
        return view('ratings.create', compact('product'));
    }

    /**
     * Store a newly created rating in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);
        
        $user = auth()->user();
        
        // Check if user already rated this product
        $existingRating = Rate::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->first();
            
        if ($existingRating) {
            return redirect()->route('rates.edit', $existingRating)
                ->with('info', 'You already rated this product. You can update your rating.');
        }
        
        // Create new rating
        $validated['user_id'] = $user->id;
        Rate::create($validated);
        
        return redirect()->route('products.show', $validated['product_id'])
            ->with('success', 'Rating submitted successfully.');
    }

    /**
     * Display the specified rating.
     */
    public function show(Rate $rate)
    {
        $user = auth()->user();
        
        // Authorization check
        if (!$user->isAdmin() && $rate->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $rate->load('product', 'user');
        
        return view('ratings.show', compact('rate'));
    }

    /**
     * Show the form for editing the specified rating.
     */
    public function edit(Rate $rate)
    {
        $user = auth()->user();
        
        // Authorization check
        if (!$user->isAdmin() && $rate->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $rate->load('product');
        
        return view('ratings.edit', compact('rate'));
    }

    /**
     * Update the specified rating in storage.
     */
    public function update(Request $request, Rate $rate)
    {
        $user = auth()->user();
        
        // Authorization check
        if (!$user->isAdmin() && $rate->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);
        
        $rate->update($validated);
        
        return redirect()->route('products.show', $rate->product_id)
            ->with('success', 'Rating updated successfully.');
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy(Rate $rate)
    {
        $user = auth()->user();
        
        // Authorization check
        if (!$user->isAdmin() && $rate->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $productId = $rate->product_id;
        $rate->delete();
        
        return redirect()->route('products.show', $productId)
            ->with('success', 'Rating deleted successfully.');
    }
}
