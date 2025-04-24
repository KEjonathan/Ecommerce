<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        // Load categories with parent info
        $categories = Category::with('parent')
            ->orderBy('name')
            ->paginate(15);
            
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        // Get all categories for parent selection
        $categories = Category::where('status', true)->get();
        
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'boolean',
        ]);
        
        // Set default values for checkboxes
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Check that parent category is not the same as the one being created
        if (isset($validated['parent_id']) && $validated['parent_id'] === $request->id) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own parent.'])->withInput();
        }
        
        Category::create($validated);
        
        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category with its products.
     */
    public function show(Category $category, Request $request)
    {
        // Load category products and subcategories
        $category->load('children');
        
        // Get products in this category and its subcategories
        $productQuery = $category->products()->where('status', true);
        
        // Add subcategory products
        if ($category->children->isNotEmpty()) {
            $subcategoryIds = $category->children->pluck('id')->toArray();
            foreach ($subcategoryIds as $subcategoryId) {
                $productQuery->orWhere('category_id', $subcategoryId);
            }
        }
        
        // Filter by availability
        if ($request->has('availability')) {
            $availability = $request->availability;
            if ($availability === 'morning') {
                $productQuery->where('is_morning_available', true);
            } elseif ($availability === 'afternoon') {
                $productQuery->where('is_afternoon_available', true);
            } elseif ($availability === 'evening') {
                $productQuery->where('is_evening_available', true);
            }
        }
        
        // Search by name or description
        if ($request->has('search')) {
            $search = $request->search;
            $productQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $products = $productQuery->paginate(12);
        
        return view('categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        // Get all categories for parent selection except this one and its children
        $categories = Category::where('id', '!=', $category->id)
            ->where('status', true)
            ->get();
            
        // Filter out child categories to avoid circular reference
        $childCategoryIds = $this->getAllChildCategoryIds($category);
        $categories = $categories->reject(function ($cat) use ($childCategoryIds) {
            return in_array($cat->id, $childCategoryIds);
        });
        
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'boolean',
        ]);
        
        // Set default values for checkboxes
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // Check that parent category is not the same as the one being edited
        if (isset($validated['parent_id']) && $validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own parent.'])->withInput();
        }
        
        // Check that parent is not one of this category's children
        $childIds = $this->getAllChildCategoryIds($category);
        if (isset($validated['parent_id']) && in_array($validated['parent_id'], $childIds)) {
            return back()->withErrors(['parent_id' => 'Cannot set a child category as parent.'])->withInput();
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }
        
        $category->update($validated);
        
        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products.');
        }
        
        // Move child categories to parent category if exists
        if ($category->children()->count() > 0) {
            $parentId = $category->parent_id;
            foreach ($category->children as $child) {
                $child->parent_id = $parentId;
                $child->save();
            }
        }
        
        // Delete the category image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
    
    /**
     * Get all child category IDs recursively.
     */
    private function getAllChildCategoryIds(Category $category, array &$ids = []): array
    {
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $this->getAllChildCategoryIds($child, $ids);
        }
        
        return $ids;
    }
}
