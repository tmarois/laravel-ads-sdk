<?php namespace LaravelAds\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelAdsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../Config/google-ads.php'=>(function_exists('config_path') ? config_path('google-ads.php') : 'google-ads.php')],
            'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Console commands
        /*$this->commands([
            RefreshTokenCommand::class,
        ]);

        $this->app->bind(GoogleAds::class, function ($app) {
            return new GoogleAds();
        });*/

    }
}
