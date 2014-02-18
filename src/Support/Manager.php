<?php
namespace OAuth\Support;

use Guzzle\Http\Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class Manager
{
    /**
     * Build and return a ServiceInterface object.
     * 
     * @param  string $service
     * @param  string $redirectUri
     * @param  array $scope
     * @return OAuth\ServiceInterface
     */
    public function consumer($service, $redirectUri = null, $scope = null)
    {
        // use scope from config if not provided
        if (is_null($scope))
        {
            $scopes = Config::get('php-oauth::oauth.consumers.'. $service .'.scopes', array());
        }

        // Default redirect URI.
        $redirectUri = $redirectUri ?: URL::current();

        // Generate class name.
        $class = '\OAuth\Services\\'. studly_case($service);

        // Get the credentials.
        $credentials = array_only(
            Config::get('php-oauth::oauth.consumers.'. $service), 
            array('client_id', 'client_secret')
        );

        // Create consumer class.
        $consumer = new $class(App::make('Guzzle\Http\Client'));

        // Configure the consumer and return it.
        return $consumer
            ->setScopes($scopes)
            ->setRedirectUri($redirectUri)
            ->setCredentials($credentials);
    }
}