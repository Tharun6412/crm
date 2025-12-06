<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // Paginator
        Paginator::useBootstrapFive();
        // Custom helpers
        require_once app_path('Helpers/auth.php');
        require_once app_path('Helpers/utils.php');
    }
}
