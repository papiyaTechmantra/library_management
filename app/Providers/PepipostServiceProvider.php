<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PepipostLib\PepipostClient;

class PepipostServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PepipostClient::class, function ($app) {
            $apiKey = env('PEPIPOST_API_KEY');
            return new PepipostClient($apiKey);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
