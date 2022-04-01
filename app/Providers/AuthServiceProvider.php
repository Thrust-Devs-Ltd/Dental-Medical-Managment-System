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

        //system access levels
        Gate::define('Super-Administrator-Dashboard', function ($user) {
            if ($user->UserRole->name == "Super Administrator") {
                return true;
            }
            return false;
        });

        Gate::define('Admin-Dashboard', function ($user) {
            if ($user->UserRole->name == "Administrator") {
                return true;
            }
            return false;
        });

        Gate::define('Doctor-Dashboard', function ($user) {
            if ($user->UserRole->name == "Doctor") {
                return true;
            }
            return false;
        });

        Gate::define('Receptionist-Dashboard', function ($user) {
            if ($user->UserRole->name == "Receptionist") {
                return true;
            }
            return false;
        });

        Gate::define('Nurse-Dashboard', function ($user) {
            if ($user->UserRole->name == "Nurse") {
                return true;
            }
            return false;
        });

        //individual records permissions
        Gate::define('action-settings', function ($user, $model) {
            // If user is administrator, then can edit any data
            if ($user->UserRole->name == "Administrator") {
                return true;
            } elseif ($user->id == $model->_who_added) {
                // Check if user is the data author
                return true;
            }

            return true;
        });


    }
}
