<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Build base query with permission filters
        $query = Property::query();

        if ($user->role === 'district_admin') {
            $query->where('district_id', $user->district_id);
        } elseif ($user->role === 'user') {
            $query->where('created_by', $user->id);
        }

        // Statistics Cards
        $totalProperties = $query->count();
        $withTenants = (clone $query)->where('has_tenant', true)->count();
        $thisMonth = (clone $query)->whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year)
                                   ->count();
        $totalArea = (clone $query)->sum('total_area');

        // Period Statistics (Last 6 months)
        $periodStats = $this->getPeriodStatistics($query);

        // Line Chart Data (Last 6 months)
        $lineChartData = $this->getLineChartData($query);

        // Bar Chart Data (By Districts)
        $barChartData = $this->getBarChartData($user);

        // Recent Activities
        $recentActivities = $this->getRecentActivities($user);

        return view('dashboard_new', compact(
            'totalProperties',
            'withTenants',
            'thisMonth',
            'totalArea',
            'periodStats',
            'lineChartData',
            'barChartData',
            'recentActivities'
        ));
    }

    private function getPeriodStatistics($baseQuery)
    {
        $periods = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $query = clone $baseQuery;
            $monthTotal = $query->whereBetween('created_at', [$monthStart, $monthEnd])->count();

            $query = clone $baseQuery;
            $monthWithTenants = $query->whereBetween('created_at', [$monthStart, $monthEnd])
                                      ->where('has_tenant', true)
                                      ->count();

            $query = clone $baseQuery;
            $monthArea = $query->whereBetween('created_at', [$monthStart, $monthEnd])
                               ->sum('total_area');

            $percentage = $monthTotal > 0 ? round(($monthWithTenants / $monthTotal) * 100) : 0;

            $periods[] = [
                'period' => $date->locale('uz_Latn')->translatedFormat('F Y'),
                'total' => $monthTotal,
                'with_tenants' => $monthWithTenants,
                'percentage' => $percentage,
                'area' => $monthArea
            ];
        }

        return $periods;
    }

    private function getLineChartData($baseQuery)
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $query = clone $baseQuery;
            $count = $query->whereBetween('created_at', [$monthStart, $monthEnd])->count();

            $labels[] = $date->locale('uz_Latn')->translatedFormat('M');
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getBarChartData($user)
    {
        $query = Property::select('district_id', DB::raw('count(*) as count'))
                        ->with('district')
                        ->groupBy('district_id');

        if ($user->role === 'district_admin') {
            $query->where('district_id', $user->district_id);
        } elseif ($user->role === 'user') {
            $query->where('created_by', $user->id);
        }

        $results = $query->get();

        $labels = [];
        $data = [];

        foreach ($results as $result) {
            $labels[] = $result->district->name ?? 'Noma\'lum';
            $data[] = $result->count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getRecentActivities($user)
    {
        $query = Property::with(['creator', 'district'])
                        ->orderBy('created_at', 'desc')
                        ->limit(10);

        if ($user->role === 'district_admin') {
            $query->where('district_id', $user->district_id);
        } elseif ($user->role === 'user') {
            $query->where('created_by', $user->id);
        }

        $properties = $query->get();

        $activities = [];

        foreach ($properties as $property) {
            $activities[] = [
                'action' => 'Yangi mulk qo\'shildi: ' . ($property->owner_name ?? 'Noma\'lum'),
                'user' => $property->creator->name ?? 'Noma\'lum',
                'district' => $property->district->name ?? null,
                'time' => $property->created_at->diffForHumans(),
                'url' => route('properties.show', $property->id)
            ];
        }

        return $activities;
    }

    // API endpoint for dynamic chart data
    public function getChartData(Request $request, $type)
    {
        $user = auth()->user();
        $period = $request->get('period', '6m');

        $query = Property::query();

        if ($user->role === 'district_admin') {
            $query->where('district_id', $user->district_id);
        } elseif ($user->role === 'user') {
            $query->where('created_by', $user->id);
        }

        if ($type === 'line') {
            $months = match($period) {
                '3m' => 3,
                '6m' => 6,
                '1y' => 12,
                default => 6
            };

            $labels = [];
            $data = [];

            for ($i = $months - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();

                $queryClone = clone $query;
                $count = $queryClone->whereBetween('created_at', [$monthStart, $monthEnd])->count();

                $labels[] = $date->locale('uz_Latn')->translatedFormat('M');
                $data[] = $count;
            }

            return response()->json([
                'labels' => $labels,
                'data' => $data
            ]);
        }

        if ($type === 'bar') {
            return response()->json($this->getBarChartData($user));
        }

        return response()->json(['error' => 'Invalid chart type'], 400);
    }
}
