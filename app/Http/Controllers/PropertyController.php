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
            $query->where(function ($q) use ($search) {
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

        // Load mahallas and streets if old district_id exists (for validation errors)
        $mahallas = [];
        $streets = [];

        if (old('district_id')) {
            $mahallas = Mahalla::where('district_id', old('district_id'))->get();
            $streets = Street::where('district_id', old('district_id'))->get();
        }

        return view('properties.create', compact('districts', 'mahallas', 'streets'));
    }
    public function store(Request $request)
    {
        // Custom validation rules
        $rules = [
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

            // Area calculation fields
            'area_length' => 'nullable|numeric|min:0.01',
            'area_width' => 'nullable|numeric|min:0.01',
            'coordinate_lat' => 'nullable|array',
            'coordinate_lat.*' => 'nullable|numeric|between:-90,90',
            'coordinate_lng' => 'nullable|array',
            'coordinate_lng.*' => 'nullable|numeric|between:-180,180',
            'calculated_land_area' => 'nullable|numeric|min:0',
            'area_calculation_method' => 'nullable|string|max:255',

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
            'adjacent_facilities' => 'required|array|min:1',

            // Geolocation
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'polygon_coordinates' => 'nullable|json',

            // Files
            'images' => 'required|array|min:4',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'act_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'design_code_file' => 'nullable|file|mimes:pdf,doc,docx,dwg,zip|max:10240'
        ];

        // Custom error messages
        $messages = [
            'building_cadastr_number.required' => 'Kadastr raqami kiritilishi shart',
            'building_cadastr_number.unique' => 'Bu kadastr raqami allaqachon mavjud',
            'owner_stir_pinfl.required' => 'STIR/PINFL kiritilishi shart',
            'owner_name.required' => 'Korxona nomi yoki F.I.SH kiritilishi shart',
            'district_id.required' => 'Tumanni tanlash shart',
            'mahalla_id.required' => 'Mahallani tanlash shart',
            'street_id.required' => 'Ko\'chani tanlash shart',
            'house_number.required' => 'Uy raqami kiritilishi shart',
            'director_name.required' => 'Rahbar F.I.SH kiritilishi shart',
            'phone_number.required' => 'Telefon raqami kiritilishi shart',
            'building_facade_length.required' => 'Fasad uzunligi kiritilishi shart',
            'summer_terrace_sides.required' => 'Yozgi terassa tomonlari kiritilishi shart',
            'distance_to_roadway.required' => 'Yo\'lgacha masofa kiritilishi shart',
            'distance_to_sidewalk.required' => 'Trotuargacha masofa kiritilishi shart',
            'total_area.required' => 'Umumiy maydon kiritilishi shart',
            'usage_purpose.required' => 'Foydalanish maqsadini tanlash shart',
            'activity_type.required' => 'Faoliyat turi kiritilishi shart',
            'terrace_buildings_available.required' => 'Terassada qurilmalar mavjudligini belgilash shart',
            'terrace_buildings_permanent.required' => 'Doimiy qurilmalar mavjudligini belgilash shart',
            'has_permit.required' => 'Ruxsatnoma mavjudligini belgilash shart',
            'adjacent_activity_type.required' => 'Tutash hududdagi faoliyat turi kiritilishi shart',
            'adjacent_activity_land.required' => 'Tutash hudud maydoni kiritilishi shart',
            'adjacent_facilities.required' => 'Tutash hududdagi qurilmalarni tanlash shart',
            'adjacent_facilities.min' => 'Kamida bitta qurilmani tanlang',
            'images.required' => 'Rasmlar yuklash shart',
            'images.min' => 'Kamida 4 ta rasm yuklash kerak',
            'images.*.image' => 'Faqat rasm fayllarini yuklash mumkin',
            'images.*.mimes' => 'Faqat JPEG, PNG, JPG formatidagi rasmlar qabul qilinadi',
            'images.*.max' => 'Rasm fayli 2MB dan oshmasligi kerak',
            'act_file.mimes' => 'Akt fayli PDF, DOC, DOCX formatida bo\'lishi kerak',
            'act_file.max' => 'Akt fayli 10MB dan oshmasligi kerak',
            'design_code_file.mimes' => 'Loyiha kodi fayli PDF, DOC, DOCX, DWG, ZIP formatida bo\'lishi kerak',
            'design_code_file.max' => 'Loyiha kodi fayli 10MB dan oshmasligi kerak'
        ];

        // First validate everything except files
        $basicRules = $rules;
        unset($basicRules['images'], $basicRules['images.*'], $basicRules['act_file'], $basicRules['design_code_file']);

        try {
            $validated = $request->validate($basicRules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Store uploaded files temporarily if basic validation fails
            $tempFiles = $this->storeTemporaryFiles($request);
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('temp_files', $tempFiles);
        }

        // Validate STIR/PINFL with Didox API
        $ownerValidation = $this->didoxService->validateStirPinfl($validated['owner_stir_pinfl']);
        if (!$ownerValidation['success']) {
            $tempFiles = $this->storeTemporaryFiles($request);
            return back()
                ->withErrors(['owner_stir_pinfl' => 'STIR/PINFL tekshirishda xato: ' . $ownerValidation['error']])
                ->withInput()
                ->with('temp_files', $tempFiles);
        }

        // Now validate files separately
        try {
            $fileRules = [
                'images' => 'required|array|min:4',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
                'act_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'design_code_file' => 'nullable|file|mimes:pdf,doc,docx,dwg,zip|max:10240'
            ];
            $request->validate($fileRules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $tempFiles = $this->storeTemporaryFiles($request);
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('temp_files', $tempFiles);
        }

        $validated['owner_api_data'] = $ownerValidation['data'];
        $validated['owner_verified'] = true;

        // Update owner name from API if available and different
        if (!empty($ownerValidation['name']) && $ownerValidation['name'] !== $validated['owner_name']) {
            $validated['owner_name'] = $ownerValidation['name'];
        }

        // Validate tenant if exists
        if (!empty($validated['has_tenant']) && !empty($validated['tenant_stir_pinfl'])) {
            $tenantValidation = $this->didoxService->validateStirPinfl($validated['tenant_stir_pinfl']);
            if ($tenantValidation['success']) {
                $validated['tenant_api_data'] = $tenantValidation['data'];
                $validated['tenant_verified'] = true;

                // Update tenant name from API if available
                if (!empty($tenantValidation['name'])) {
                    $validated['tenant_name'] = $tenantValidation['name'];
                }
            } else {
                return back()
                    ->withErrors(['tenant_stir_pinfl' => 'Ijarachi STIR/PINFL tekshirishda xato: ' . $tenantValidation['error']])
                    ->withInput();
            }
        }

        // Handle file uploads - use temp files if they exist, otherwise upload new ones
        $tempFiles = session('temp_files');

        if ($tempFiles) {
            // Move temporary files to permanent location
            $fileData = $this->moveTempFilesToPermanent($tempFiles);
            $validated['images'] = $fileData['images'];
            $validated['act_file'] = $fileData['act_file'];
            $validated['design_code_file'] = $fileData['design_code_file'];

            // Clear temp files from session
            session()->forget('temp_files');
        } else {
            // Handle fresh file uploads
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
        }
        $validated['created_by'] = auth()->id();

        // Generate Google Maps URL if coordinates provided
        if (!empty($validated['latitude']) && !empty($validated['longitude'])) {
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

        // Remove coordinate arrays from validated data (they're not in DB schema)
        unset($validated['coordinate_lat'], $validated['coordinate_lng']);

        // Create the property
        $property = Property::create($validated);

        $message = 'Mulk muvaffaqiyatli qo\'shildi va STIR/PINFL tasdiqlandi!';

        return redirect()->route('properties.index')->with('success', $message);
    }


    public function show(Property $property)
    {
        // Check permissions
        if (!auth()->user()->canViewProperty($property)) {
            abort(403, 'Bu mulkni ko\'rish uchun ruxsatingiz yo\'q');
        }

        // Load all relationships
        $property->load([
            'district',
            'mahalla',
            'street',
            'creator' => function ($query) {
                $query->select('id', 'name', 'email', 'role');
            }
        ]);

        // Calculate additional data
        $property->calculated_perimeter = null;
        if ($property->area_length && $property->area_width) {
            $property->calculated_perimeter = 2 * ($property->area_length + $property->area_width);
        }

        // Format creation date
        $property->formatted_created_at = $property->created_at->format('d.m.Y H:i');
        $property->formatted_updated_at = $property->updated_at->format('d.m.Y H:i');

        // Check if files exist
        $property->images_exist = collect($property->images)->filter(function ($image) {
            return Storage::disk('public')->exists($image);
        });

        $property->act_file_exists = $property->act_file && Storage::disk('public')->exists($property->act_file);
        $property->design_code_file_exists = $property->design_code_file && Storage::disk('public')->exists($property->design_code_file);

        // Get file sizes
        if ($property->act_file_exists) {
            $property->act_file_size = Storage::disk('public')->size($property->act_file);
        }

        if ($property->design_code_file_exists) {
            $property->design_code_file_size = Storage::disk('public')->size($property->design_code_file);
        }

        // Format adjacent facilities
        $facilityLabels = [
            'kapital_qurilma' => 'Kapital qurilma',
            'mavjud_emas' => 'Mavjud emas',
            'yengil_qurilma' => 'Yengil qurilma',
            'bostirma' => 'Bostirma',
            'beton_maydoncha' => 'Beton maydoncha',
            'elektr_quvvatlash' => 'Elektr quvvatlash',
            'avtoturargoh' => 'Avtoturargoh',
            'boshqalar' => 'Boshqalar',
        ];

        $property->formatted_adjacent_facilities = collect($property->adjacent_facilities)
            ->map(function ($facility) use ($facilityLabels) {
                return $facilityLabels[$facility] ?? $facility;
            });

        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        if (!auth()->user()->canViewProperty($property)) {
            abort(403);
        }

        $districts = District::where('is_active', true)->get();
        $mahallas = Mahalla::where('district_id', $property->district_id)->get();
        $streets = Street::where('district_id', $property->district_id)->get();

        return view('properties.edit', compact('property', 'districts', 'mahallas', 'streets'));
    }

    private function storeTemporaryFiles(Request $request)
    {
        $tempFiles = [
            'images' => [],
            'act_file' => null,
            'design_code_file' => null
        ];

        // Store images temporarily
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if ($image->isValid()) {
                    $tempPath = $image->store('temp/images', 'public');
                    $tempFiles['images'][] = [
                        'path' => $tempPath,
                        'original_name' => $image->getClientOriginalName(),
                        'size' => $image->getSize(),
                        'index' => $index
                    ];
                }
            }
        }

        // Store act file temporarily
        if ($request->hasFile('act_file') && $request->file('act_file')->isValid()) {
            $tempPath = $request->file('act_file')->store('temp/acts', 'public');
            $tempFiles['act_file'] = [
                'path' => $tempPath,
                'original_name' => $request->file('act_file')->getClientOriginalName(),
                'size' => $request->file('act_file')->getSize()
            ];
        }

        // Store design code file temporarily
        if ($request->hasFile('design_code_file') && $request->file('design_code_file')->isValid()) {
            $tempPath = $request->file('design_code_file')->store('temp/design_codes', 'public');
            $tempFiles['design_code_file'] = [
                'path' => $tempPath,
                'original_name' => $request->file('design_code_file')->getClientOriginalName(),
                'size' => $request->file('design_code_file')->getSize()
            ];
        }

        return $tempFiles;
    }

    /**
     * Move temporary files to permanent location
     */
    private function moveTempFilesToPermanent($tempFiles)
    {
        $permanentFiles = [
            'images' => [],
            'act_file' => null,
            'design_code_file' => null
        ];

        // Move images
        if (!empty($tempFiles['images'])) {
            foreach ($tempFiles['images'] as $tempImage) {
                $newPath = str_replace('temp/images/', 'properties/images/', $tempImage['path']);
                if (Storage::disk('public')->move($tempImage['path'], $newPath)) {
                    $permanentFiles['images'][] = $newPath;
                }
            }
        }

        // Move act file
        if (!empty($tempFiles['act_file'])) {
            $newPath = str_replace('temp/acts/', 'properties/acts/', $tempFiles['act_file']['path']);
            if (Storage::disk('public')->move($tempFiles['act_file']['path'], $newPath)) {
                $permanentFiles['act_file'] = $newPath;
            }
        }

        // Move design code file
        if (!empty($tempFiles['design_code_file'])) {
            $newPath = str_replace('temp/design_codes/', 'properties/design_codes/', $tempFiles['design_code_file']['path']);
            if (Storage::disk('public')->move($tempFiles['design_code_file']['path'], $newPath)) {
                $permanentFiles['design_code_file'] = $newPath;
            }
        }

        return $permanentFiles;
    }

    /**
     * Clean up temporary files
     */
    private function cleanupTempFiles($tempFiles)
    {
        if (!empty($tempFiles['images'])) {
            foreach ($tempFiles['images'] as $tempImage) {
                Storage::disk('public')->delete($tempImage['path']);
            }
        }

        if (!empty($tempFiles['act_file'])) {
            Storage::disk('public')->delete($tempFiles['act_file']['path']);
        }

        if (!empty($tempFiles['design_code_file'])) {
            Storage::disk('public')->delete($tempFiles['design_code_file']['path']);
        }
    }

    public function update(Request $request, Property $property)
    {
        // Check permissions
        if (!auth()->user()->canViewProperty($property) || !auth()->user()->hasPermission('edit')) {
            abort(403, 'Bu mulkni tahrirlash uchun ruxsatingiz yo\'q');
        }

        // Custom validation rules
        $rules = [
            'building_cadastr_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('properties')->ignore($property->id)
            ],
            'owner_stir_pinfl' => 'required|string|max:20',
            'owner_name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'mahalla_id' => 'required|exists:mahallas,id',
            'street_id' => 'required|exists:streets,id',
            'house_number' => 'required|string|max:20',
            'director_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'area_length' => 'nullable|numeric|min:0.01',
            'area_width' => 'nullable|numeric|min:0.01',
            'coordinate_lat' => 'nullable|array',
            'coordinate_lat.*' => 'nullable|numeric|between:-90,90',
            'coordinate_lng' => 'nullable|array',
            'coordinate_lng.*' => 'nullable|numeric|between:-180,180',
            'calculated_land_area' => 'nullable|numeric|min:0',
            'area_calculation_method' => 'nullable|string|max:255',
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
            'adjacent_facilities' => 'required|array|min:1',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'polygon_coordinates' => 'nullable|json',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'temp_image_paths' => 'nullable|array',
            'temp_image_paths.*' => 'string',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
            'act_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'temp_act_file' => 'nullable|string',
            'delete_act_file' => 'nullable|boolean',
            'design_code_file' => 'nullable|file|mimes:pdf,doc,docx,dwg,zip|max:10240',
            'temp_design_code_file' => 'nullable|string',
            'delete_design_code_file' => 'nullable|boolean',
        ];

        $messages = [
            'building_cadastr_number.required' => 'Kadastr raqami kiritilishi shart',
            'building_cadastr_number.unique' => 'Bu kadastr raqami allaqachon mavjud',
            'owner_stir_pinfl.required' => 'STIR/PINFL kiritilishi shart',
            'owner_name.required' => 'Korxona nomi yoki F.I.SH kiritilishi shart',
            'district_id.required' => 'Tumanni tanlash shart',
            'mahalla_id.required' => 'Mahallani tanlash shart',
            'street_id.required' => 'Ko\'chani tanlash shart',
            'house_number.required' => 'Uy raqami kiritilishi shart',
            'director_name.required' => 'Rahbar F.I.SH kiritilishi shart',
            'phone_number.required' => 'Telefon raqami kiritilishi shart',
            'building_facade_length.required' => 'Fasad uzunligi kiritilishi shart',
            'summer_terrace_sides.required' => 'Yozgi terassa tomonlari kiritilishi shart',
            'distance_to_roadway.required' => 'Yo\'lgacha masofa kiritilishi shart',
            'distance_to_sidewalk.required' => 'Trotuargacha masofa kiritilishi shart',
            'total_area.required' => 'Umumiy maydon kiritilishi shart',
            'usage_purpose.required' => 'Foydalanish maqsadini tanlash shart',
            'activity_type.required' => 'Faoliyat turi kiritilishi shart',
            'terrace_buildings_available.required' => 'Terassada qurilmalar mavjudligini belgilash shart',
            'terrace_buildings_permanent.required' => 'Doimiy qurilmalar mavjudligini belgilash shart',
            'has_permit.required' => 'Ruxsatnoma mavjudligini belgilash shart',
            'adjacent_activity_type.required' => 'Tutash hududdagi faoliyat turi kiritilishi shart',
            'adjacent_activity_land.required' => 'Tutash hudud maydoni kiritilishi shart',
            'adjacent_facilities.required' => 'Tutash hududdagi qurilmalarni tanlash shart',
            'adjacent_facilities.min' => 'Kamida bitta qurilmani tanlang',
            'images.*.image' => 'Faqat rasm fayllarini yuklash mumkin',
            'images.*.mimes' => 'Faqat JPEG, PNG, JPG formatidagi rasmlar qabul qilinadi',
            'images.*.max' => 'Rasm fayli 2MB dan oshmasligi kerak',
            'act_file.mimes' => 'Akt fayli PDF, DOC, DOCX formatida bo\'lishi kerak',
            'act_file.max' => 'Akt fayli 10MB dan oshmasligi kerak',
            'design_code_file.mimes' => 'Loyiha kodi fayli PDF, DOC, DOCX, DWG, ZIP formatida bo\'lishi kerak',
            'design_code_file.max' => 'Loyiha kodi fayli 10MB dan oshmasligi kerak'
        ];

        // Basic validation
        $basicRules = $rules;
        unset(
            $basicRules['images'],
            $basicRules['images.*'],
            $basicRules['temp_image_paths'],
            $basicRules['temp_image_paths.*'],
            $basicRules['delete_images'],
            $basicRules['delete_images.*'],
            $basicRules['act_file'],
            $basicRules['temp_act_file'],
            $basicRules['delete_act_file'],
            $basicRules['design_code_file'],
            $basicRules['temp_design_code_file'],
            $basicRules['delete_design_code_file']
        );

        try {
            $validated = $request->validate($basicRules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $tempFiles = $this->storeTemporaryFiles($request);
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('temp_files', $tempFiles);
        }

        // Validate STIR/PINFL only if changed
        if ($property->owner_stir_pinfl !== $validated['owner_stir_pinfl']) {
            $ownerValidation = $this->didoxService->validateStirPinfl($validated['owner_stir_pinfl']);
            if (!$ownerValidation['success']) {
                $tempFiles = $this->storeTemporaryFiles($request);
                return back()
                    ->withErrors(['owner_stir_pinfl' => 'STIR/PINFL tekshirishda xato: ' . $ownerValidation['error']])
                    ->withInput()
                    ->with('temp_files', $tempFiles);
            }

            $validated['owner_api_data'] = $ownerValidation['data'];
            $validated['owner_verified'] = true;

            if (!empty($ownerValidation['name']) && $ownerValidation['name'] !== $validated['owner_name']) {
                $validated['owner_name'] = $ownerValidation['name'];
            }
        }

        // Validate tenant if exists and changed
        if (!empty($validated['has_tenant']) && !empty($validated['tenant_stir_pinfl'])) {
            if ($property->tenant_stir_pinfl !== $validated['tenant_stir_pinfl']) {
                $tenantValidation = $this->didoxService->validateStirPinfl($validated['tenant_stir_pinfl']);
                if ($tenantValidation['success']) {
                    $validated['tenant_api_data'] = $tenantValidation['data'];
                    $validated['tenant_verified'] = true;

                    if (!empty($tenantValidation['name'])) {
                        $validated['tenant_name'] = $tenantValidation['name'];
                    }
                } else {
                    $tempFiles = $this->storeTemporaryFiles($request);
                    return back()
                        ->withErrors(['tenant_stir_pinfl' => 'Ijarachi STIR/PINFL tekshirishda xato: ' . $tenantValidation['error']])
                        ->withInput()
                        ->with('temp_files', $tempFiles);
                }
            }
        } else {
            $validated['tenant_name'] = null;
            $validated['tenant_stir_pinfl'] = null;
            $validated['tenant_activity_type'] = null;
            $validated['tenant_api_data'] = null;
            $validated['tenant_verified'] = false;
        }

        // File validation
        try {
            $fileRules = [
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
                'act_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'design_code_file' => 'nullable|file|mimes:pdf,doc,docx,dwg,zip|max:10240'
            ];
            $request->validate($fileRules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $tempFiles = $this->storeTemporaryFiles($request);
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('temp_files', $tempFiles);
        }

        // Handle images
        $tempFiles = session('temp_files');
        $currentImages = $property->images ?? [];

        // Delete images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $index) {
                if (isset($currentImages[$index])) {
                    Storage::disk('public')->delete($currentImages[$index]);
                    unset($currentImages[$index]);
                }
            }
            $currentImages = array_values($currentImages);
        }

        // Add new images
        if ($tempFiles && !empty($tempFiles['images'])) {
            $fileData = $this->moveTempFilesToPermanent($tempFiles);
            $currentImages = array_merge($currentImages, $fileData['images']);
            session()->forget('temp_files');
        } elseif ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $currentImages[] = $image->store('properties/images', 'public');
            }
        } elseif ($request->has('temp_image_paths')) {
            foreach ($request->temp_image_paths as $tempPath) {
                $newPath = str_replace('temp/images/', 'properties/images/', $tempPath);
                if (Storage::disk('public')->exists($tempPath)) {
                    if (Storage::disk('public')->move($tempPath, $newPath)) {
                        $currentImages[] = $newPath;
                    }
                }
            }
        }

        $validated['images'] = $currentImages;

        // Handle act file
        if ($request->has('delete_act_file') && $request->delete_act_file) {
            if ($property->act_file) {
                Storage::disk('public')->delete($property->act_file);
            }
            $validated['act_file'] = null;
        } elseif ($tempFiles && !empty($tempFiles['act_file'])) {
            if ($property->act_file) {
                Storage::disk('public')->delete($property->act_file);
            }
            $fileData = $this->moveTempFilesToPermanent($tempFiles);
            $validated['act_file'] = $fileData['act_file'];
        } elseif ($request->hasFile('act_file')) {
            if ($property->act_file) {
                Storage::disk('public')->delete($property->act_file);
            }
            $validated['act_file'] = $request->file('act_file')->store('properties/acts', 'public');
        } elseif ($request->has('temp_act_file')) {
            $tempPath = $request->temp_act_file;
            $newPath = str_replace('temp/acts/', 'properties/acts/', $tempPath);
            if (Storage::disk('public')->exists($tempPath)) {
                if ($property->act_file) {
                    Storage::disk('public')->delete($property->act_file);
                }
                if (Storage::disk('public')->move($tempPath, $newPath)) {
                    $validated['act_file'] = $newPath;
                }
            }
        } else {
            $validated['act_file'] = $property->act_file;
        }

        // Handle design code file
        if ($request->has('delete_design_code_file') && $request->delete_design_code_file) {
            if ($property->design_code_file) {
                Storage::disk('public')->delete($property->design_code_file);
            }
            $validated['design_code_file'] = null;
        } elseif ($tempFiles && !empty($tempFiles['design_code_file'])) {
            if ($property->design_code_file) {
                Storage::disk('public')->delete($property->design_code_file);
            }
            $fileData = $this->moveTempFilesToPermanent($tempFiles);
            $validated['design_code_file'] = $fileData['design_code_file'];
        } elseif ($request->hasFile('design_code_file')) {
            if ($property->design_code_file) {
                Storage::disk('public')->delete($property->design_code_file);
            }
            $validated['design_code_file'] = $request->file('design_code_file')->store('properties/design_codes', 'public');
        } elseif ($request->has('temp_design_code_file')) {
            $tempPath = $request->temp_design_code_file;
            $newPath = str_replace('temp/design_codes/', 'properties/design_codes/', $tempPath);
            if (Storage::disk('public')->exists($tempPath)) {
                if ($property->design_code_file) {
                    Storage::disk('public')->delete($property->design_code_file);
                }
                if (Storage::disk('public')->move($tempPath, $newPath)) {
                    $validated['design_code_file'] = $newPath;
                }
            }
        } else {
            $validated['design_code_file'] = $property->design_code_file;
        }

        // Generate Google Maps URL
        if (!empty($validated['latitude']) && !empty($validated['longitude'])) {
            $validated['google_maps_url'] = "https://www.google.com/maps?q={$validated['latitude']},{$validated['longitude']}";
        }

        // Parse polygon coordinates
        if (!empty($validated['polygon_coordinates'])) {
            $polygonData = json_decode($validated['polygon_coordinates'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $validated['polygon_coordinates'] = $polygonData;
            } else {
                $validated['polygon_coordinates'] = null;
            }
        }

        // Remove coordinate arrays
        unset($validated['coordinate_lat'], $validated['coordinate_lng']);
        unset($validated['temp_image_paths'], $validated['delete_images']);
        unset($validated['temp_act_file'], $validated['delete_act_file']);
        unset($validated['temp_design_code_file'], $validated['delete_design_code_file']);

        // Update the property
        $property->update($validated);

        $message = 'Mulk muvaffaqiyatli yangilandi!';
        if (isset($ownerValidation) && $ownerValidation['success']) {
            $message .= ' STIR/PINFL tasdiqlandi!';
        }

        return redirect()->route('properties.show', $property)->with('success', $message);
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
