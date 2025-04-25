<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query();
        $selectedCategory = null;
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
            $selectedCategory = Category::find($request->category_id);
        }
        
        if ($request->has('availability')) {
            $availability = $request->availability;
            if ($availability === 'morning') {
                $query->where('is_morning_available', true);
            } elseif ($availability === 'afternoon') {
                $query->where('is_afternoon_available', true);
            } elseif ($availability === 'evening') {
                $query->where('is_evening_available', true);
            }
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $query->latest();
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $query->withCount('ratings')
                          ->orderByDesc('ratings_count');
                    break;
                default:
                    $query->orderBy('name');
                    break;
            }
        } else {
            $query->orderBy('name');
        }
        
        $products = $query->with(['category', 'supplier'])
            ->where('status', true)
            ->paginate(12);
            
        $categories = Category::where('status', true)->get();
        
        return view('products.index', compact('products', 'categories', 'selectedCategory'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::where('status', true)->get();
        $suppliers = Supplier::where('status', true)->get();
        
        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_morning_available' => 'boolean',
            'is_afternoon_available' => 'boolean',
            'is_evening_available' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'status' => 'boolean',
        ]);
        
        $validated['is_morning_available'] = $request->has('is_morning_available') ? 1 : 0;
        $validated['is_afternoon_available'] = $request->has('is_afternoon_available') ? 1 : 0;
        $validated['is_evening_available'] = $request->has('is_evening_available') ? 1 : 0;
        $validated['status'] = $request->has('status') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Load related data
        $product->load(['category', 'supplier', 'ratings.user']);
        
        // Get related products in the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->limit(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->get();
        $suppliers = Supplier::where('status', true)->get();
        
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_morning_available' => 'boolean',
            'is_afternoon_available' => 'boolean',
            'is_evening_available' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'status' => 'boolean',
        ]);
        
        // Set default values for checkboxes if not present
        $validated['is_morning_available'] = $request->has('is_morning_available') ? 1 : 0;
        $validated['is_afternoon_available'] = $request->has('is_afternoon_available') ? 1 : 0;
        $validated['is_evening_available'] = $request->has('is_evening_available') ? 1 : 0;
        $validated['status'] = $request->has('status') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete the product image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
