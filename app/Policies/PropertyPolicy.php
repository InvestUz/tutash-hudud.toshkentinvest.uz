<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view properties list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Property $property): bool
    {
        // Super admin can view all properties
        if ($user->role === 'super_admin') {
            return true;
        }

        // District admin can view properties in their district
        if ($user->role === 'district_admin') {
            return $user->district_id === $property->district_id;
        }

        // Regular users can only view their own properties
        return $user->id === $property->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create properties
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): bool
    {
        // Super admin can update all properties
        if ($user->role === 'super_admin') {
            return true;
        }

        // District admin can update properties in their district
        if ($user->role === 'district_admin') {
            return $user->district_id === $property->district_id;
        }

        // Regular users can only update their own properties
        return $user->id === $property->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        // Only super admin can delete properties
        if ($user->role === 'super_admin') {
            return true;
        }

        // District admin can delete properties in their district
        if ($user->role === 'district_admin') {
            return $user->district_id === $property->district_id;
        }

        // Regular users cannot delete properties (or only their own if you want)
        return false; // Change to: $user->id === $property->created_by; if you want users to delete their own
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Property $property): bool
    {
        // Only super admin can restore properties
        return $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Property $property): bool
    {
        // Only super admin can permanently delete properties
        return $user->role === 'super_admin';
    }
}
