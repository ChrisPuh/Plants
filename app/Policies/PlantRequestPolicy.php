<?php

namespace App\Policies;

use App\Models\PlantRequest;
use App\Models\User;

class PlantRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role->isAdmin(); // Only admins can view all requests
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PlantRequest $plantRequest): bool
    {
        return $user->role->isAdmin() || $user->id === $plantRequest->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return ! $user->role->isAdmin(); // Only regular users can create requests, admins create plants directly
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PlantRequest $plantRequest): bool
    {
        return $user->role->isAdmin(); // Only admins can approve/reject requests
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PlantRequest $plantRequest): bool
    {
        return $user->role->isAdmin() || $user->id === $plantRequest->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PlantRequest $plantRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PlantRequest $plantRequest): bool
    {
        return false;
    }
}
