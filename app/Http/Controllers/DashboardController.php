<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\District;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get statistics based on user role
        $query = Property::query();

        if ($user->role !== 'super_admin') {
            if ($user->role === 'district_admin') {
                $query->where('district_id', $user->district_id);
            } else {
                $query->where('created_by', $user->id);
            }
        }

        $totalProperties = $query->count();
        $propertiesWithTenants = (clone $query)->where('has_tenant', true)->count();
        $recentProperties = (clone $query)->latest()->take(5)->with(['district', 'mahalla', 'street'])->get();

        // Activity types statistics - SQL GROUP BY muammosini hal qilish
        $activityTypes = (clone $query)->select('activity_type')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('activity_type')
            ->orderByDesc('count') // Count bo'yicha tartibga solish
            ->get();

        // District statistics (for super admin)
        $districtStats = null;
        if ($user->role === 'super_admin') {
            $districtStats = District::withCount('properties')->get();
        }

        return view('dashboard', compact(
            'totalProperties',
            'propertiesWithTenants',
            'recentProperties',
            'activityTypes',
            'districtStats'
        ));
    }
}
