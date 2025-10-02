<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        //Verifica si estamos en produccion para forzar el uso de HTTPS
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
