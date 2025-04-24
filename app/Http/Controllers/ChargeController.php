<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    /**
     * Display a listing of the charges.
     */
    public function index()
    {
        $charges = Charge::orderBy('name')->paginate(15);
        return view('charges.index', compact('charges'));
    }

    /**
     * Show the form for creating a new charge.
     */
    public function create()
    {
        return view('charges.create');
    }

    /**
     * Store a newly created charge in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_percentage' => 'boolean',
            'status' => 'boolean',
        ]);
        
        // Set default values for checkboxes
        $validated['is_percentage'] = $request->has('is_percentage') ? 1 : 0;
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // If it's a percentage, ensure it's a valid percentage
        if ($validated['is_percentage'] && $validated['amount'] > 100) {
            return back()->withErrors(['amount' => 'Percentage cannot be greater than 100%.'])->withInput();
        }
        
        Charge::create($validated);
        
        return redirect()->route('charges.index')
            ->with('success', 'Charge created successfully.');
    }

    /**
     * Display the specified charge.
     */
    public function show(Charge $charge)
    {
        return view('charges.show', compact('charge'));
    }

    /**
     * Show the form for editing the specified charge.
     */
    public function edit(Charge $charge)
    {
        return view('charges.edit', compact('charge'));
    }

    /**
     * Update the specified charge in storage.
     */
    public function update(Request $request, Charge $charge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_percentage' => 'boolean',
            'status' => 'boolean',
        ]);
        
        // Set default values for checkboxes
        $validated['is_percentage'] = $request->has('is_percentage') ? 1 : 0;
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // If it's a percentage, ensure it's a valid percentage
        if ($validated['is_percentage'] && $validated['amount'] > 100) {
            return back()->withErrors(['amount' => 'Percentage cannot be greater than 100%.'])->withInput();
        }
        
        $charge->update($validated);
        
        return redirect()->route('charges.index')
            ->with('success', 'Charge updated successfully.');
    }

    /**
     * Remove the specified charge from storage.
     */
    public function destroy(Charge $charge)
    {
        $charge->delete();
        
        return redirect()->route('charges.index')
            ->with('success', 'Charge deleted successfully.');
    }
    
    /**
     * Toggle the status of the charge.
     */
    public function toggleStatus(Charge $charge)
    {
        $charge->status = !$charge->status;
        $charge->save();
        
        return redirect()->route('charges.index')
            ->with('success', 'Charge status updated successfully.');
    }
}
