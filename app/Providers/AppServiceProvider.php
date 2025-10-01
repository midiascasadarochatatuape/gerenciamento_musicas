<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar duração do remember token para 1 ano
        // O Laravel usa este valor para determinar quando o remember token expira
        $this->configureRememberTokenLifetime();
    }

    /**
     * Configure the remember token lifetime to 1 year
     */
    protected function configureRememberTokenLifetime(): void
    {
        // 1 ano = 365 dias * 24 horas * 60 minutos = 525600 minutos
        $oneYearInMinutes = 525600;

        // Configurar o tempo de vida do remember token
        config(['auth.remember_token_lifetime' => $oneYearInMinutes]);
    }
}
