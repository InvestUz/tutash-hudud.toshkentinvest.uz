<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Mahalla;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LocationController extends Controller
{
    /**
     * Get mahallas by district
     */
    public function getMahallas(Request $request): JsonResponse
    {
        try {
            $districtId = $request->get('district_id');
            
            if (!$districtId) {
                return response()->json([
                    'success' => false,
                    'message' => 'District ID is required'
                ], 400);
            }

            $mahallas = Mahalla::where('district_id', $districtId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json($mahallas);

        } catch (\Exception $e) {
            \Log::error('Error fetching mahallas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching mahallas'
            ], 500);
        }
    }

    /**
     * Get streets by district - FIXED
     */
    public function getStreets(Request $request): JsonResponse
    {
        try {
            $districtId = $request->get('district_id');
            
            if (!$districtId) {
                return response()->json([
                    'success' => false,
                    'message' => 'District ID is required'
                ], 400);
            }

            // FIXED: Use district_id instead of mahalla_id
            $streets = Street::where('district_id', $districtId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'district_id']);

            \Log::info("Fetching streets for district {$districtId}: found " . $streets->count() . " streets");

            return response()->json($streets);

        } catch (\Exception $e) {
            \Log::error('Error fetching streets: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching streets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store new mahalla
     */
    public function storeMahalla(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'district_id' => 'required|exists:districts,id'
            ]);

            $mahalla = Mahalla::create([
                'name' => $validated['name'],
                'district_id' => $validated['district_id'],
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mahalla created successfully',
                'mahalla' => $mahalla
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating mahalla: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating mahalla'
            ], 500);
        }
    }

    /**
     * Store new street - FIXED
     */
    public function storeStreet(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'district_id' => 'required|exists:districts,id'
            ]);

            // FIXED: Check for existing street by district_id and name
            $existingStreet = Street::where('district_id', $validated['district_id'])
                ->where('name', $validated['name'])
                ->first();

            if ($existingStreet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu tumanda bunday nomli ko\'cha allaqachon mavjud'
                ], 422);
            }

            // FIXED: Create street with district_id
            $street = Street::create([
                'name' => $validated['name'],
                'district_id' => $validated['district_id'],
                'is_active' => true
            ]);

            \Log::info("Created new street: {$street->name} in district {$street->district_id}");

            return response()->json([
                'success' => true,
                'message' => 'Street created successfully',
                'street' => $street
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating street: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating street: ' . $e->getMessage()
            ], 500);
        }
    }
}