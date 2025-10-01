<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function edit(User $authUser, User $user)
    {
        return $authUser->id === $user->id || $authUser->type_user === 'admin';
    }

    public function update(User $authUser, User $user)
    {
        return $authUser->id === $user->id;;
    }
}
