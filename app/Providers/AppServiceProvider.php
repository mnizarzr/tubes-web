<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config as MidtransConfig;

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
        MidtransConfig::$serverKey = config('services.midtrans.key');
        MidtransConfig::$isProduction = false;
        MidtransConfig::$overrideNotifUrl = config('app.url').'/webhook/midtrans';
    }
}
