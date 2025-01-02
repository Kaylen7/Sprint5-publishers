<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::enablePasswordGrant();
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        $this->loadRoutesFrom(base_path('routes/passport.php'));
        Passport::hashClientSecrets();
        Passport::tokensExpireIn(now()->addDays(5));
        Passport::refreshTokensExpireIn(now()->addDays(15));
        Passport::personalAccessTokensExpireIn(now()->addDays(20));
    }
}
