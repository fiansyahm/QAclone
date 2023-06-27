<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
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

        Gate::define('superadmin', function (User $user) {
            return $user->is_superAdmin;
        });
        Gate::define('admin', function (User $user) {
            return $user->is_admin;
        });
        Gate::define('reviewer', function (User $user) {
            return $user->is_reviewer;
        });
        Gate::define('projectSummary', function (User $user) {
            return $user->is_superAdmin || $user->is_admin;
        });
    }
}
