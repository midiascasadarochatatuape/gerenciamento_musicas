<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Song;
use App\Models\User;
use App\Models\Devocional;
use App\Policies\SongPolicy;
use App\Policies\UserPolicy;
use App\Policies\DevocionalPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Song::class => SongPolicy::class,
        User::class => UserPolicy::class,
        Devocional::class => DevocionalPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
