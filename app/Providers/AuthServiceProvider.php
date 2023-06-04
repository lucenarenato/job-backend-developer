<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //this is to make the cors package work also on authentication endpoints
        Route::middleware('cors')->group(function() {
            Passport::routes();
            /* Passport::tokensExpireIn(Carbon::now()->addHours(24));
            Passport::refreshTokensExpireIn(Carbon::now()->addDays(30)); */
            Passport::personalAccessTokensExpireIn(now()->addDays(30));
        });
    }
}
