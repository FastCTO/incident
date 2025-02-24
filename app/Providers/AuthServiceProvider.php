<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\WaveToken;
use App\Http\Controllers\WaveAuthController;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Future Model-to-Policy mappings go here
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Hook into user authentication to ensure Wave Token is available
        Auth::viaRequest('wave-token-refresh', function ($request) {
            if (!Auth::check()) {
                return null;
            }

            $userId = Auth::id();
            $waveToken = WaveToken::where('user_id', $userId)->first();

            // If token is missing or expired, fetch a new one
            if (!$waveToken || Carbon::now()->greaterThan($waveToken->expires_at)) {
                $authController = new WaveAuthController();
                return $authController->authenticate();
            }

            return $waveToken;
        });
    }
}

