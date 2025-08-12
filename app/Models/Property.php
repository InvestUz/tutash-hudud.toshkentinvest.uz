<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_name',
        'owner_company',
        'owner_stir_pinfl',
        'building_cadastr_number',
        'object_name',
        'activity_type',
        'has_tenant',
        'tenant_name',
        'tenant_company',
        'tenant_stir_pinfl',
        'district_id',
        'mahalla_id',
        'street_id',
        'house_number',
        'additional_info',
        'adjacent_activity_type',
        'adjacent_facilities',
        'latitude',
        'longitude',
        'google_maps_url',
        'images',
        'act_file',
        'created_by'
    ];

    protected $casts = [
        'has_tenant' => 'boolean',
        'adjacent_facilities' => 'array',
        'images' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

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
}
