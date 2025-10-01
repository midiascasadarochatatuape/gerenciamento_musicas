<?php

namespace App\Policies;

use App\Models\Devocional;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DevocionalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Devocional $devocional): bool
    {
        return true; // Everyone can view individual devotionals
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->type_user, ['admin', 'tecnico']); // Only admins and technicians
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Devocional $devocional): bool
    {
        return in_array($user->type_user, ['admin', 'tecnico']) ||
               $devocional->user_id === $user->id; // Admin/tech or author
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Devocional $devocional): bool
    {
        return $user->type_user === 'admin' ||
               $devocional->user_id === $user->id; // Admin or author
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Devocional $devocional): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Devocional $devocional): bool
    {
        return false;
    }
}
