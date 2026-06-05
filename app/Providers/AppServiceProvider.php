<?php

namespace App\Providers;

use App\Notifications\Channels\SmsChannel;
use Carbon\CarbonInterval;
use DateInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
// use Laravel\Passport\Passport;

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
        
        // SMS Notification
        Notification::extend('sms', function ($app) {
            return new SmsChannel();
        });

        // RateLimit
        RateLimiter::for('balance-check', function(Request $request) {
            return Limit::perHour(50)->by($request->user()?->id ?? $request->ip());
        });

        // Custom helpers
        require_once app_path('Helpers/auth.php');
        require_once app_path('Helpers/utils.php');

        // Passport token configuration
        // Passport::clientCredentialsTokensExpireIn(new DateInterval('PT5M'));
    }
}
