<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\District;
use App\Models\Mahalla;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['district', 'mahalla', 'street', 'creator']);

        // User permission check
        if (auth()->user()->role !== 'super_admin') {
            if (auth()->user()->role === 'district_admin') {
                $query->where('district_id', auth()->user()->district_id);
            } else {
                $query->where('created_by', auth()->id());
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('owner_name', 'like', "%{$search}%")
                  ->orWhere('object_name', 'like', "%{$search}%")
                  ->orWhere('building_cadastr_number', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

               if ($request->filled('tenant_activity_type')) {
            $query->where('tenant_activity_type', $request->tenant_activity_type);
        }

        
        $properties = $query->paginate(15);
        $districts = District::where('is_active', true)->get();

        return view('properties.index', compact('properties', 'districts'));
    }

    public function create()
    {
        $districts = District::where('is_active', true)->get();
        return view('properties.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_name' => 'required|string|max:255',
            'owner_stir_pinfl' => 'required|string|max:20',
            'building_cadastr_number' => 'required|string|max:50',
            'object_name' => 'required|string|max:255',
            'activity_type' => 'required|string|max:255',
            'tenant_activity_type' => 'nullable',
            'has_tenant' => 'boolean',
            'tenant_name' => 'nullable|string|max:255',
            'tenant_stir_pinfl' => 'nullable|string|max:20',
            'district_id' => 'required|exists:districts,id',
            'mahalla_id' => 'required|exists:mahallas,id',
            'street_id' => 'required|exists:streets,id',
            'house_number' => 'required|string|max:20',
            'additional_info' => 'nullable|string',
            'adjacent_activity_type' => 'required|string|max:255',
            'adjacent_activity_land' => 'required|string|max:255',
            'adjacent_facilities' => 'required|array',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images' => 'required|array|min:4',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'act_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240'
        ]);

        // Handle file uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('properties/images', 'public');
            }
        }

        $actFilePath = null;
        if ($request->hasFile('act_file')) {
            $actFilePath = $request->file('act_file')->store('properties/acts', 'public');
        }

        $validated['images'] = $imagePaths;
        $validated['act_file'] = $actFilePath;
        $validated['created_by'] = auth()->id();

        // Generate Google Maps URL if coordinates provided
        if ($validated['latitude'] && $validated['longitude']) {
            $validated['google_maps_url'] = "https://www.google.com/maps?q={$validated['latitude']},{$validated['longitude']}";
        }

        Property::create($validated);

        return redirect()->route('properties.index')->with('success', 'Mulk muvaffaqiyatli qo\'shildi!');
    }

    public function show(Property $property)
    {
        if (!auth()->user()->canViewProperty($property)) {
            abort(403);
        }

        $property->load(['district', 'mahalla', 'street', 'creator']);
        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        if (!auth()->user()->canViewProperty($property)) {
            abort(403);
        }

        $districts = District::where('is_active', true)->get();
        $mahallas = Mahalla::where('district_id', $property->district_id)->get();
        $streets = Street::where('mahalla_id', $property->mahalla_id)->get();

        return view('properties.edit', compact('property', 'districts', 'mahallas', 'streets'));
    }

    public function update(Request $request, Property $property)
    {
        if (!auth()->user()->canViewProperty($property)) {
            abort(403);
        }

        $validated = $request->validate([
            'owner_name' => 'required|string|max:255',
            'owner_stir_pinfl' => 'required|string|max:20',
            'building_cadastr_number' => 'required|string|max:50',
            'object_name' => 'required|string|max:255',
            'activity_type' => 'required|string|max:255',
            'has_tenant' => 'boolean',
            'tenant_name' => 'nullable|string|max:255',
            'tenant_stir_pinfl' => 'nullable|string|max:20',
            'district_id' => 'required|exists:districts,id',
            'mahalla_id' => 'required|exists:mahallas,id',
            'street_id' => 'required|exists:streets,id',
            'house_number' => 'required|string|max:20',
            'additional_info' => 'nullable|string',
            'adjacent_activity_type' => 'required|string|max:255',
            'adjacent_activity_land' => 'required|string|max:255',
            'adjacent_facilities' => 'required|array',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        // Handle new images if uploaded
        if ($request->hasFile('images')) {
            $request->validate([
                'images' => 'array|min:4',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Delete old images
            foreach ($property->images as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('properties/images', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        // Handle act file
        if ($request->hasFile('act_file')) {
            $request->validate([
                'act_file' => 'file|mimes:pdf,doc,docx|max:10240'
            ]);

            if ($property->act_file) {
                Storage::disk('public')->delete($property->act_file);
            }

            $validated['act_file'] = $request->file('act_file')->store('properties/acts', 'public');
        }

        // Generate Google Maps URL if coordinates provided
        if ($validated['latitude'] && $validated['longitude']) {
            $validated['google_maps_url'] = "https://www.google.com/maps?q={$validated['latitude']},{$validated['longitude']}";
        }

        $property->update($validated);

        return redirect()->route('properties.show', $property)->with('success', 'Mulk ma\'lumotlari yangilandi!');
    }

    public function destroy(Property $property)
    {
        if (!auth()->user()->canViewProperty($property) || !auth()->user()->hasPermission('delete')) {
            abort(403);
        }

        // Delete files
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image);
        }

        if ($property->act_file) {
            Storage::disk('public')->delete($property->act_file);
        }

        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Mulk o\'chirildi!');
    }

    
}
