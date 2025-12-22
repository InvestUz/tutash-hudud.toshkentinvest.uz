<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        // Cadastral and Building Information
        'building_cadastr_number',
        'owner_stir_pinfl',
        'owner_name',

        // Address Information
        'district_id',
        'mahalla_id',
        'street_id',
        'house_number',

        // Contact Information
        'director_name',
        'phone_number',

        // NEW: Area measurements (Length x Width)
        'area_length',
        'area_width',
        'calculated_land_area',
        'area_calculation_method',

        // Building Measurements
        'building_facade_length',
        'summer_terrace_sides',
        'distance_to_roadway',
        'distance_to_sidewalk',
        'total_area',

        // Usage Information
        'usage_purpose',
        'terrace_buildings_available',
        'terrace_buildings_permanent',
        'has_permit',

        // Activity types
        'activity_type',
        'tenant_activity_type',

        // Tenant Information
        'has_tenant',
        'tenant_name',
        'tenant_stir_pinfl',

        // Additional Information
        'additional_info',

        // Adjacent Area Information
        'adjacent_activity_type',
        'adjacent_activity_land',
        'adjacent_facilities',

        // Geolocation
        'latitude',
        'longitude',
        'google_maps_url',
        'polygon_coordinates',

        // Files
        'images',
        'act_file',
        'design_code_file',

        // API Integration
        'owner_api_data',
        'tenant_api_data',
        'owner_verified',
        'tenant_verified',

        // Creator
        'created_by',

        // Monitoring status
        'needs_monitoring',
        'monitoring_file',
        'monitoring_comment',
        // Contract Information
        'shartnoma_raqami',
        'shartnoma_sanasi',
        'shartnoma_tizimga_kiritilgan_vaqti',
       'shartnoma_summasi',

    ];

    protected $casts = [
        'adjacent_facilities' => 'array',
        'polygon_coordinates' => 'array',
        'images' => 'array',
        'owner_api_data' => 'array',
        'tenant_api_data' => 'array',
        'owner_verified' => 'boolean',
        'tenant_verified' => 'boolean',
        'has_tenant' => 'boolean',
        'area_length' => 'decimal:2',
        'area_width' => 'decimal:2',
        'calculated_land_area' => 'decimal:2',
        'total_area' => 'decimal:2',
        'building_facade_length' => 'decimal:2',
        'summer_terrace_sides' => 'decimal:2',
        'distance_to_roadway' => 'decimal:2',
        'distance_to_sidewalk' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'needs_monitoring' => 'boolean',
        'monitoring_comment' => 'string',
    ];

    // Relationships
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function mahalla()
    {
        return $this->belongsTo(Mahalla::class);
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // NEW: Calculate area automatically
    public function updateCalculatedArea()
    {
        if ($this->area_length && $this->area_width) {
            $this->calculated_land_area = $this->area_length * $this->area_width;
            $this->area_calculation_method = 'To\'rtburchak formulasi';
            $this->save();

            return $this->calculated_land_area;
        }

        return null;
    }

    // NEW: Get formatted area with calculation method
    public function getFormattedAreaAttribute()
    {
        if ($this->calculated_land_area) {
            return "{$this->calculated_land_area} m² ({$this->area_calculation_method})";
        }

        return "{$this->total_area} m² (Manual)";
    }

    // NEW: Validate area consistency
    public function isAreaConsistent($tolerance = 5)
    {
        if (!$this->area_length || !$this->area_width || !$this->total_area) {
            return true; // Skip validation if data is incomplete
        }

        $calculatedArea = $this->area_length * $this->area_width;
        $difference = abs($calculatedArea - $this->total_area);
        $percentDifference = ($difference / $calculatedArea) * 100;

        return $percentDifference <= $tolerance;
    }

    // NEW: Get area calculation details
    public function getAreaCalculationDetails()
    {
        return [
            'length' => $this->area_length,
            'width' => $this->area_width,
            'calculated_area' => $this->calculated_land_area,
            'manual_area' => $this->total_area,
            'method' => $this->area_calculation_method,
            'consistent' => $this->isAreaConsistent(),
            'formula' => $this->area_length && $this->area_width
                ? "{$this->area_length} × {$this->area_width} = {$this->calculated_land_area} m²"
                : null
        ];
    }

    // Get full address
    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->district->name ?? null,
            $this->mahalla->name ?? null,
            $this->street->name ?? null,
            $this->house_number
        ]));
    }

    // Get adjacent facilities as readable text
    public function getAdjacentFacilitiesTextAttribute()
    {
        if (empty($this->adjacent_facilities)) {
            return 'Ma\'lumot yo\'q';
        }

        $facilitiesMap = [
            'kapital_qurilma' => 'Kapital qurilma',
            'mavjud_emas' => 'Mavjud emas',
            'yengil_qurilma' => 'Yengil qurilma',
            'bostirma' => 'Bostirma',
            'beton_maydoncha' => 'Beton maydoncha',
            'elektr_quvvatlash' => 'Elektr quvvatlash',
            'avtoturargoh' => 'Avtoturargoh',
            'boshqalar' => 'Boshqalar',
        ];

        return collect($this->adjacent_facilities)
            ->map(function($facility) use ($facilitiesMap) {
                return $facilitiesMap[$facility] ?? $facility;
            })
            ->join(', ');
    }

    // Scopes
    public function scopeWithVerifiedOwner($query)
    {
        return $query->where('owner_verified', true);
    }

    public function scopeWithTenant($query)
    {
        return $query->where('has_tenant', true);
    }

    public function scopeByDistrict($query, $districtId)
    {
        return $query->where('district_id', $districtId);
    }

    public function scopeByUsagePurpose($query, $purpose)
    {
        return $query->where('usage_purpose', $purpose);
    }

    // NEW: Scope for area range filtering
    public function scopeByAreaRange($query, $minArea = null, $maxArea = null)
    {
        if ($minArea) {
            $query->where('calculated_land_area', '>=', $minArea);
        }

        if ($maxArea) {
            $query->where('calculated_land_area', '<=', $maxArea);
        }

        return $query;
    }
}
