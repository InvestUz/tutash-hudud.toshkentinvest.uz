<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Mahalla;
use App\Models\Street;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getMahallas(Request $request)
    {
        $mahallas = Mahalla::where('district_id', $request->district_id)
            ->where('is_active', true)
            ->get();

        return response()->json($mahallas);
    }

    public function getStreets(Request $request)
    {
        $streets = Street::where('mahalla_id', $request->mahalla_id)
            ->where('is_active', true)
            ->get();

        return response()->json($streets);
    }

    public function storeMahalla(Request $request)
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255'
        ]);

        $mahalla = Mahalla::create($validated);

        return response()->json([
            'success' => true,
            'mahalla' => $mahalla
        ]);
    }

    public function storeStreet(Request $request)
    {
        $validated = $request->validate([
            'mahalla_id' => 'required|exists:mahallas,id',
            'name' => 'required|string|max:255'
        ]);

        $street = Street::create($validated);

        return response()->json([
            'success' => true,
            'street' => $street
        ]);
    }
}
