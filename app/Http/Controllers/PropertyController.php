<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\District;
use App\Models\Mahalla;
use App\Models\Street;
use App\Services\DidoxApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    protected $didoxService;

    public function __construct(DidoxApiService $didoxService)
    {
        $this->didoxService = $didoxService;
    }

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
                  ->orWhere('building_cadastr_number', 'like', "%{$search}%")
                  ->orWhere('director_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
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

        if ($request->filled('verified_only')) {
            $query->where('owner_verified', true);
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
            // Cadastral and Basic Info
            'building_cadastr_number' => 'required|string|max:50|unique:properties',
            'owner_stir_pinfl' => 'required|string|max:20',
            'owner_name' => 'required|string|max:255',

            // Address
            'district_id' => 'required|exists:districts,id',
            'mahalla_id' => 'required|exists:mahallas,id',
            'street_id' => 'required|exists:streets,id',
            'house_number' => 'required|string|max:20',

            // Contact Information
            'director_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',

            // Building Measurements
            'building_facade_length' => 'required|numeric|min:0',
            'summer_terrace_sides' => 'required|numeric|min:0',
            'distance_to_roadway' => 'required|numeric|min:0',
            'distance_to_sidewalk' => 'required|numeric|min:0',
            'total_area' => 'required|numeric|min:0',

            // Usage Information
            'usage_purpose' => 'required|string|max:255',
            'terrace_buildings_available' => 'required|in:Xa,Yoq',
            'terrace_buildings_permanent' => 'required|in:Xa,Yoq',
            'has_permit' => 'required|in:Xa,Yoq',

            // Activity types
            'activity_type' => 'required|string|max:255',
            'tenant_activity_type' => 'nullable|string|max:255',

            // Tenant Information
            'has_tenant' => 'boolean',
            'tenant_name' => 'nullable|string|max:255',
            'tenant_stir_pinfl' => 'nullable|string|max:20',

            // Additional Information
            'additional_info' => 'nullable|string',

            // Adjacent Area Information
            'adjacent_activity_type' => 'required|string|max:255',
            'adjacent_activity_land' => 'required|string|max:255',
            'adjacent_facilities' => 'required|array',

            // Geolocation
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'polygon_coordinates' => 'nullable|json',

            // Files
            'images' => 'required|array|min:4',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'act_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'design_code_file' => 'nullable|file|mimes:pdf,doc,docx,dwg,zip|max:10240'
        ]);

        // Validate STIR/PINFL with Didox API
        $ownerValidation = $this->didoxService->validateStirPinfl($validated['owner_stir_pinfl']);
        if (!$ownerValidation['success']) {
            return back()->withErrors(['owner_stir_pinfl' => 'STIR/PINFL tekshirishda xato: ' . $ownerValidation['error']])
                        ->withInput();
        }

        $validated['owner_api_data'] = $ownerValidation['data'];
        $validated['owner_verified'] = true;

        // Update owner name from API if available
        if (!empty($ownerValidation['name'])) {
            $validated['owner_name'] = $ownerValidation['name'];
        }

        // Validate tenant if exists
        if ($validated['has_tenant'] && !empty($validated['tenant_stir_pinfl'])) {
            $tenantValidation = $this->didoxService->validateStirPinfl($validated['tenant_stir_pinfl']);
            if ($tenantValidation['success']) {
                $validated['tenant_api_data'] = $tenantValidation['data'];
                $validated['tenant_verified'] = true;

                // Update tenant name from API if available
                if (!empty($tenantValidation['name'])) {
                    $validated['tenant_name'] = $tenantValidation['name'];
                }
            }
        }

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

        $designCodeFilePath = null;
        if ($request->hasFile('design_code_file')) {
            $designCodeFilePath = $request->file('design_code_file')->store('properties/design_codes', 'public');
        }

        $validated['images'] = $imagePaths;
        $validated['act_file'] = $actFilePath;
        $validated['design_code_file'] = $designCodeFilePath;
        $validated['created_by'] = auth()->id();

        // Generate Google Maps URL if coordinates provided
        if ($validated['latitude'] && $validated['longitude']) {
            $validated['google_maps_url'] = "https://www.google.com/maps?q={$validated['latitude']},{$validated['longitude']}";
        }

        // Parse polygon coordinates if provided
        if (!empty($validated['polygon_coordinates'])) {
            $polygonData = json_decode($validated['polygon_coordinates'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $validated['polygon_coordinates'] = $polygonData;
            } else {
                $validated['polygon_coordinates'] = null;
            }
        }

        Property::create($validated);

        return redirect()->route('properties.index')->with('success', 'Mulk muvaffaqiyatli qo\'shildi va STIR/PINFL tasdiqlandi!');
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
            // All the same validation rules as store method
            'building_cadastr_number' => ['required', 'string', 'max:50', Rule::unique('properties')->ignore($property->id)],
            'owner_stir_pinfl' => 'required|string|max:20',
            'owner_name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'mahalla_id' => 'required|exists:mahallas,id',
            'street_id' => 'required|exists:streets,id',
            'house_number' => 'required|string|max:20',
            'director_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'building_facade_length' => 'required|numeric|min:0',
            'summer_terrace_sides' => 'required|numeric|min:0',
            'distance_to_roadway' => 'required|numeric|min:0',
            'distance_to_sidewalk' => 'required|numeric|min:0',
            'total_area' => 'required|numeric|min:0',
            'usage_purpose' => 'required|string|max:255',
            'terrace_buildings_available' => 'required|in:Xa,Yoq',
            'terrace_buildings_permanent' => 'required|in:Xa,Yoq',
            'has_permit' => 'required|in:Xa,Yoq',
            'activity_type' => 'required|string|max:255',
            'tenant_activity_type' => 'nullable|string|max:255',
            'has_tenant' => 'boolean',
            'tenant_name' => 'nullable|string|max:255',
            'tenant_stir_pinfl' => 'nullable|string|max:20',
            'additional_info' => 'nullable|string',
            'adjacent_activity_type' => 'required|string|max:255',
            'adjacent_activity_land' => 'required|string|max:255',
            'adjacent_facilities' => 'required|array',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'polygon_coordinates' => 'nullable|json'
        ]);

        // Re-validate STIR/PINFL if changed
        if ($validated['owner_stir_pinfl'] !== $property->owner_stir_pinfl) {
            $ownerValidation = $this->didoxService->validateStirPinfl($validated['owner_stir_pinfl']);
            if (!$ownerValidation['success']) {
                return back()->withErrors(['owner_stir_pinfl' => 'STIR/PINFL tekshirishda xato: ' . $ownerValidation['error']])
                            ->withInput();
            }

            $validated['owner_api_data'] = $ownerValidation['data'];
            $validated['owner_verified'] = true;

            if (!empty($ownerValidation['name'])) {
                $validated['owner_name'] = $ownerValidation['name'];
            }
        }

        // Handle tenant validation
        if ($validated['has_tenant'] && !empty($validated['tenant_stir_pinfl'])) {
            if ($validated['tenant_stir_pinfl'] !== $property->tenant_stir_pinfl) {
                $tenantValidation = $this->didoxService->validateStirPinfl($validated['tenant_stir_pinfl']);
                if ($tenantValidation['success']) {
                    $validated['tenant_api_data'] = $tenantValidation['data'];
                    $validated['tenant_verified'] = true;

                    if (!empty($tenantValidation['name'])) {
                        $validated['tenant_name'] = $tenantValidation['name'];
                    }
                }
            }
        } else {
            $validated['tenant_api_data'] = null;
            $validated['tenant_verified'] = false;
        }

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

        // Handle other files
        if ($request->hasFile('act_file')) {
            $request->validate([
                'act_file' => 'file|mimes:pdf,doc,docx|max:10240'
            ]);

            if ($property->act_file) {
                Storage::disk('public')->delete($property->act_file);
            }

            $validated['act_file'] = $request->file('act_file')->store('properties/acts', 'public');
        }

        if ($request->hasFile('design_code_file')) {
            $request->validate([
                'design_code_file' => 'file|mimes:pdf,doc,docx,dwg,zip|max:10240'
            ]);

            if ($property->design_code_file) {
                Storage::disk('public')->delete($property->design_code_file);
            }

            $validated['design_code_file'] = $request->file('design_code_file')->store('properties/design_codes', 'public');
        }

        // Generate Google Maps URL if coordinates provided
        if ($validated['latitude'] && $validated['longitude']) {
            $validated['google_maps_url'] = "https://www.google.com/maps?q={$validated['latitude']},{$validated['longitude']}";
        }

        // Parse polygon coordinates if provided
        if (!empty($validated['polygon_coordinates'])) {
            $polygonData = json_decode($validated['polygon_coordinates'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $validated['polygon_coordinates'] = $polygonData;
            } else {
                $validated['polygon_coordinates'] = null;
            }
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

        if ($property->design_code_file) {
            Storage::disk('public')->delete($property->design_code_file);
        }

        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Mulk o\'chirildi!');
    }

    /**
     * AJAX method to validate STIR/PINFL
     */
    public function validateStirPinfl(Request $request)
    {
        $request->validate([
            'stir_pinfl' => 'required|string'
        ]);

        $result = $this->didoxService->validateStirPinfl($request->stir_pinfl);

        return response()->json($result);
    }

    /**
     * Get property data as GeoJSON for map display
     */
    public function getGeoJson(Request $request)
    {
        $query = Property::with(['district', 'mahalla', 'street']);

        // Apply same permission filters as index
        if (auth()->user()->role !== 'super_admin') {
            if (auth()->user()->role === 'district_admin') {
                $query->where('district_id', auth()->user()->district_id);
            } else {
                $query->where('created_by', auth()->id());
            }
        }

        $properties = $query->whereNotNull('latitude')->whereNotNull('longitude')->get();

        $features = $properties->map(function ($property) {
            $feature = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$property->longitude, (float)$property->latitude]
                ],
                'properties' => [
                    'id' => $property->id,
                    'owner_name' => $property->owner_name,
                    'address' => $property->full_address,
                    'activity_type' => $property->activity_type,
                    'total_area' => $property->total_area,
                    'verified' => $property->owner_verified,
                    'has_tenant' => $property->has_tenant,
                    'cadastr_number' => $property->building_cadastr_number
                ]
            ];

            // Add polygon if available
            if ($property->hasPolygon()) {
                $feature['geometry'] = [
                    'type' => 'Polygon',
                    'coordinates' => $property->polygon_coordinates
                ];
            }

            return $feature;
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }
}
