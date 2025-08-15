<?php

namespace App\Policies;

use App\Models\PlantContribution;
use App\Models\User;

class PlantContributionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin(); // Only admins can view all contributions
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PlantContribution $plantContribution): bool
    {
        return $user->isAdmin() || $user->id === $plantContribution->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create contributions
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PlantContribution $plantContribution): bool
    {
        return $user->isAdmin(); // Only admins can approve/reject contributions
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PlantContribution $plantContribution): bool
    {
        return $user->isAdmin() || $user->id === $plantContribution->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PlantContribution $plantContribution): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PlantContribution $plantContribution): bool
    {
        return false;
    }
}
