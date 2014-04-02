<?php
namespace OAuth\Support;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Config;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->package('hannesvdvreken/php-oauth');
    }
    
    /**
     * Register method
     */
    public function register()
    {
        // Register the config file:
        $this->app['config']->package('hannesvdvreken/php-oauth', __DIR__ .'/../config');

        // bind object for OAuth Facade
        $this->app->bind('oauth', function ($app) {
            return $app->make('\OAuth\Support\Manager');
        });

        // Add an alias for the Facade.
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('OAuth', 'OAuth\Support\Facade');
    }
}
