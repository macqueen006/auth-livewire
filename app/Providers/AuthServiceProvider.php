<?php

namespace App\Providers;

use App\Http\Middleware\TwoFactorChallengedMiddleware;
use App\Http\Middleware\TwoFactorEnabledMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use PragmaRX\Google2FA\Google2FA;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind a singleton for the Google2FA service
        $this->app->singleton(Google2FA::class, function ($app) {
            return new Google2FA;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (class_exists(\Laravel\Fortify\Features::class) && config('settings.enable_2fa')) {
            Config::set('fortify.features', array_merge(
                Config::get('fortify.features', []),
                [
                    \Laravel\Fortify\Features::twoFactorAuthentication([
                        'confirm' => true,
                        'confirmPassword' => true,
                    ]),
                ]
            ));
        }
//        Route::middlewareGroup('two-factor-challenged', [TwoFactorChallengedMiddleware::class]);
//        Route::middlewareGroup('two-factor-enabled', [TwoFactorEnabledMiddleware::class]);
    }
}
