<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::verifyEmailView(fn () => hybridly('authentication::verify-email'));
        Fortify::loginView(fn () => hybridly('authentication::login'));
        Fortify::requestPasswordResetLinkView(fn () => hybridly('authentication::forgot-password'));
        Fortify::registerView(fn () => hybridly('authentication::register'));
        Fortify::resetPasswordView(function (Request $request) {
            return hybridly('authentication::reset-password', [
                'token' => $request->route('token'),
                'email' => $request->input('email'),
            ]);
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}