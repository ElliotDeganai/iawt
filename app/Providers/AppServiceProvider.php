<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        // Évite l'erreur "Specified key was too long" sur MySQL < 8 / utf8mb4
        // (même correctif que sur FreshFeed et CalTracker)
        Schema::defaultStringLength(191);
    }
}
