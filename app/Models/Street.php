<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Street extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahalla_id',
        'name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function mahalla(): BelongsTo
    {
        return $this->belongsTo(Mahalla::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
