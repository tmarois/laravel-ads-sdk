<?php

namespace LaravelAds\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelAds\Console\RefreshTokenCommand;

class LaravelAdsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../../config/google-ads.php'=>(function_exists('config_path')?config_path('google-ads.php'):'google-ads.php')],'config');

        $this->publishes([__DIR__.'/../../config/bing-ads.php'=>(function_exists('config_path')?config_path('bing-ads.php'):'bing-ads.php')],'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            RefreshTokenCommand::class,
        ]);
    }
}
