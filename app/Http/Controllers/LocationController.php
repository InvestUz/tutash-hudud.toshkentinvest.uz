<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Mahalla;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
   public function getMahallas(Request $request)
   {
       try {
           Log::info('getMahallas called with district_id: ' . $request->district_id);
           
           $query = Mahalla::where('district_id', $request->district_id);
           
           // Only add is_active condition if the column exists
           if (\Schema::hasColumn('mahallas', 'is_active')) {
               $query->where('is_active', true);
           }
           
           $mahallas = $query->orderBy('name')->get();
           
           Log::info('Found mahallas count: ' . $mahallas->count());
           
           return response()->json($mahallas);
           
       } catch (\Exception $e) {
           Log::error('Error in getMahallas: ' . $e->getMessage());
           Log::error('Stack trace: ' . $e->getTraceAsString());
           
           return response()->json([
               'error' => 'Server error: ' . $e->getMessage()
           ], 500);
       }
   }

   public function getStreets(Request $request)
   {
       try {
           Log::info('getStreets called with mahalla_id: ' . $request->mahalla_id);
           
           $query = Street::where('mahalla_id', $request->mahalla_id);
           
           // Only add is_active condition if the column exists
           if (\Schema::hasColumn('streets', 'is_active')) {
               $query->where('is_active', true);
           }
           
           $streets = $query->orderBy('name')->get();
           
           Log::info('Found streets count: ' . $streets->count());
           
           return response()->json($streets);
           
       } catch (\Exception $e) {
           Log::error('Error in getStreets: ' . $e->getMessage());
           Log::error('Stack trace: ' . $e->getTraceAsString());
           
           return response()->json([
               'error' => 'Server error: ' . $e->getMessage()
           ], 500);
       }
   }

   public function storeMahalla(Request $request)
   {
       try {
           Log::info('storeMahalla called with data: ' . json_encode($request->all()));
           
           $validated = $request->validate([
               'district_id' => 'required|exists:districts,id',
               'name' => 'required|string|max:255'
           ]);

           // Check if mahalla already exists
           $existingMahalla = Mahalla::where('district_id', $validated['district_id'])
               ->where('name', $validated['name'])
               ->first();

           if ($existingMahalla) {
               return response()->json([
                   'success' => false,
                   'message' => 'Bu mahalla allaqachon mavjud'
               ], 422);
           }

           $mahalla = Mahalla::create($validated);
           
           Log::info('Mahalla created successfully: ' . json_encode($mahalla));

           return response()->json([
               'success' => true,
               'mahalla' => $mahalla,
               'message' => 'Mahalla muvaffaqiyatli yaratildi!'
           ]);
           
       } catch (\Illuminate\Validation\ValidationException $e) {
           Log::error('Validation error in storeMahalla: ' . json_encode($e->errors()));
           
           return response()->json([
               'success' => false,
               'message' => 'Validation error: ' . implode(', ', array_flatten($e->errors()))
           ], 422);
           
       } catch (\Exception $e) {
           Log::error('Error in storeMahalla: ' . $e->getMessage());
           Log::error('Stack trace: ' . $e->getTraceAsString());
           
           return response()->json([
               'success' => false,
               'message' => 'Server error: ' . $e->getMessage()
           ], 500);
       }
   }

   public function storeStreet(Request $request)
   {
       try {
           Log::info('storeStreet called with data: ' . json_encode($request->all()));
           
           $validated = $request->validate([
               'mahalla_id' => 'required|exists:mahallas,id',
               'name' => 'required|string|max:255'
           ]);

           // Check if street already exists
           $existingStreet = Street::where('mahalla_id', $validated['mahalla_id'])
               ->where('name', $validated['name'])
               ->first();

           if ($existingStreet) {
               return response()->json([
                   'success' => false,
                   'message' => 'Bu ko\'cha allaqachon mavjud'
               ], 422);
           }

           $street = Street::create($validated);
           
           Log::info('Street created successfully: ' . json_encode($street));

           return response()->json([
               'success' => true,
               'street' => $street,
               'message' => 'Ko\'cha muvaffaqiyatli yaratildi!'
           ]);
           
       } catch (\Illuminate\Validation\ValidationException $e) {
           Log::error('Validation error in storeStreet: ' . json_encode($e->errors()));
           
           return response()->json([
               'success' => false,
               'message' => 'Validation error: ' . implode(', ', array_flatten($e->errors()))
           ], 422);
           
       } catch (\Exception $e) {
           Log::error('Error in storeStreet: ' . $e->getMessage());
           Log::error('Stack trace: ' . $e->getTraceAsString());
           
           return response()->json([
               'success' => false,
               'message' => 'Server error: ' . $e->getMessage()
           ], 500);
       }
   }
}