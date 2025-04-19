<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        // Assurez-vous que les routes API utilisent bien le préfixe "api"
        Route::prefix('api')
            ->middleware('api')  // Assurez-vous que ce middleware est correctement appliqué
            ->group(base_path('routes/api.php'));  // Charger les routes API à partir du fichier api.php
    }
}
