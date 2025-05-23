<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // 🔐 Force HTTPS for signed URL validation in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // 🐛 Log all SQL queries (for debug)
        DB::listen(function ($query) {
            Log::info($query->sql, $query->bindings);
        });
    }
}

