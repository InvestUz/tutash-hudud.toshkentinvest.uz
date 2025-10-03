<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'district_id',
        'role',
        'permissions',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'created_by');
    }

public function hasPermission(string $permission): bool
{
    if ($this->role === 'super_admin') {
        return true;
    }

    $permissions = $this->permissions;

    // Ensure $permissions is always an array
    if (is_string($permissions)) {
        $permissions = json_decode($permissions, true) ?: [];
    } elseif (!is_array($permissions)) {
        $permissions = [];
    }

    return in_array($permission, $permissions);
}


    public function canViewProperty(Property $property): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        if ($this->role === 'view_only') {
            return true;
        }

        if ($this->role === 'district_admin') {
            return $property->district_id === $this->district_id;
        }

        return $property->created_by === $this->id;
    }
}
