<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\Banner;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display about us page.
     */
    public function aboutUs()
    {
        $aboutUs = AboutUs::first();
        return view('content.about-us', compact('aboutUs'));
    }

    /**
     * Display a listing of the banners.
     */
    public function index()
    {
        $banners = Banner::where('status', true)
            ->orderBy('order', 'asc')
            ->get();
        
        return view('content.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('content.banners.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $validated['image'] = $imagePath;
        }

        Banner::create($validated);

        return redirect()->route('content.index')
            ->with('success', 'Banner created successfully');
    }

    /**
     * Display the specified banner.
     */
    public function show(Banner $content)
    {
        return view('content.banners.show', ['banner' => $content]);
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(Banner $content)
    {
        return view('content.banners.edit', ['banner' => $content]);
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, Banner $content)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $validated['image'] = $imagePath;
        }

        $content->update($validated);

        return redirect()->route('content.index')
            ->with('success', 'Banner updated successfully');
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(Banner $content)
    {
        $content->delete();

        return redirect()->route('content.index')
            ->with('success', 'Banner deleted successfully');
    }

    /**
     * Show the form for editing the about us content.
     */
    public function editAboutUs()
    {
        $aboutUs = AboutUs::first();
        if (!$aboutUs) {
            $aboutUs = new AboutUs();
        }
        
        return view('content.about-us-edit', compact('aboutUs'));
    }

    /**
     * Update the about us content.
     */
    public function updateAboutUs(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mission' => 'nullable|string|max:255',
            'vision' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('about-us', 'public');
            $validated['image'] = $imagePath;
        }

        $aboutUs = AboutUs::first();
        if ($aboutUs) {
            $aboutUs->update($validated);
        } else {
            AboutUs::create($validated);
        }

        return redirect()->route('about.us')
            ->with('success', 'About Us content updated successfully');
    }
}
