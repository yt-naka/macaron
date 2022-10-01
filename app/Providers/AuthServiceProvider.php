<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 管理者
        Gate::define('admin', function ($user) {
            return ($user->player->role == 'admin');
        });
        // 政府エディション
        Gate::define('government', function ($user) {
            return ($user->player->role == 'government');
        });
        // 商人エディション
        Gate::define('merchant', function ($user) {
            return ($user->player->role == 'merchant');
        });
    }
}
