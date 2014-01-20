<?php
namespace OAuth\Services;

use OAuth\OAuth2Service;

class Foursquare extends OAuth2Service
{
	/**
     * @var string
     */
    protected $endpointAuthorization = 'https://foursquare.com/oauth2/authenticate';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://foursquare.com/oauth2/access_token';

    /**
     * @var string
     */
    protected $base = 'https://api.foursquare.com/v2/';
}