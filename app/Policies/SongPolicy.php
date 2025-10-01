<?php

namespace App\Policies;

use App\Models\Song;
use App\Models\User;

class SongPolicy
{
    public function viewAny(User $user)
    {
        return true; // Everyone can view songs list
    }

    public function view(User $user, Song $song)
    {
        return true; // Everyone can view individual songs
    }

    public function create(User $user)
    {
        return auth()->check(); // Permite qualquer usuário autenticado
    }

    public function update(User $user, Song $song)
    {
        return in_array($user->type_user, ['admin', 'tecnico']);
    }

    public function delete(User $user, Song $song)
    {
        return in_array($user->type_user, ['admin', 'tecnico']);
    }
}
