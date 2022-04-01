<?php

namespace App\Providers;

use App\Channels\SmsNotifyChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
        // The log will be used in the Notification's via method
        // You can use whatever name your want
        Notification::extend('smsNotify', function ($app) {
            return new SmsNotifyChannel();
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
