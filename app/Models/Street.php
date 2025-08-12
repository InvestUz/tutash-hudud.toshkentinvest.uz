<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship with District
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // Scope for active streets
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for district
    public function scopeByDistrict($query, $districtId)
    {
        return $query->where('district_id', $districtId);
    }
}