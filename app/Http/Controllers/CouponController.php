<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of the coupons.
     */
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->paginate(15);
        return view('coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:20|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);
        
        // Generate a random code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = $this->generateUniqueCode();
        }
        
        // Set default values for checkboxes
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // If it's a percentage, ensure it's a valid percentage
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage cannot be greater than 100%.'])->withInput();
        }
        
        Coupon::create($validated);
        
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    /**
     * Display the specified coupon.
     */
    public function show(Coupon $coupon)
    {
        // Load related orders
        $coupon->load('orders');
        
        return view('coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);
        
        // Set default values for checkboxes
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // If it's a percentage, ensure it's a valid percentage
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage cannot be greater than 100%.'])->withInput();
        }
        
        $coupon->update($validated);
        
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon)
    {
        // Check if coupon is used in any orders
        if ($coupon->orders()->count() > 0) {
            return back()->with('error', 'Cannot delete coupon that has been used in orders.');
        }
        
        $coupon->delete();
        
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
    
    /**
     * Toggle the status of the coupon.
     */
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->status = !$coupon->status;
        $coupon->save();
        
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon status updated successfully.');
    }
    
    /**
     * Generate a unique coupon code.
     */
    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Coupon::where('code', $code)->exists());
        
        return $code;
    }
}
