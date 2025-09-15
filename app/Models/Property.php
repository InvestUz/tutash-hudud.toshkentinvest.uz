<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'created_by'
    ];

    protected $casts = [
        'has_tenant' => 'boolean',
        'owner_verified' => 'boolean',
        'tenant_verified' => 'boolean',
        'adjacent_facilities' => 'array',
        'images' => 'array',
        'polygon_coordinates' => 'array',
        'owner_api_data' => 'array',
        'tenant_api_data' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'building_facade_length' => 'decimal:2',
        'summer_terrace_sides' => 'decimal:2',
        'distance_to_roadway' => 'decimal:2',
        'distance_to_sidewalk' => 'decimal:2',
        'total_area' => 'decimal:2'
    ];

    // Relationships
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function mahalla(): BelongsTo
    {
        return $this->belongsTo(Mahalla::class);
    }

    public function street(): BelongsTo
    {
        return $this->belongsTo(Street::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return "{$this->district->name}, {$this->mahalla->name}, {$this->street->name}, {$this->house_number}";
    }

    public function getIsOwnerVerifiedAttribute()
    {
        return $this->owner_verified && !empty($this->owner_api_data);
    }

    public function getIsTenantVerifiedAttribute()
    {
        return $this->tenant_verified && !empty($this->tenant_api_data);
    }

    // Helper methods
    public function hasValidCoordinates()
    {
        return !empty($this->latitude) && !empty($this->longitude);
    }

    public function hasPolygon()
    {
        return !empty($this->polygon_coordinates) && is_array($this->polygon_coordinates);
    }

    public function getImageUrls()
    {
        if (empty($this->images)) {
            return [];
        }

        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    public function getActFileUrl()
    {
        return $this->act_file ? asset('storage/' . $this->act_file) : null;
    }

    public function getDesignCodeFileUrl()
    {
        return $this->design_code_file ? asset('storage/' . $this->design_code_file) : null;
    }
}
