<?php
/**
 * laravel-turbosms.
 * autor: evheniys
 * 
 */

namespace Evheniys\Turbosms;

use Illuminate\Support\ServiceProvider;

class TurbosmsServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__.'/../../config/turbosms.php' => config_path('turbosms.php'),
        ], 'config');

        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'turbosms');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //register edelim
        $this->app['turbosms'] = $this->app->share(function($app)
        {
            return new Turbosms($app);
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return string
     */
    public function provides()
    {
        return ['mutlucell'];
    }
}