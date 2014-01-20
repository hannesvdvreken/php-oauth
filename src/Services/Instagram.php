<?php
namespace OAuth\Services;

use OAuth\OAuth2Service;

class Instagram extends OAuth2Service
{
	/**
     * @var string
     */
    protected $endpointAuthorization = 'https://api.instagram.com/oauth/authorize';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://api.instagram.com/oauth/access_token';

    /**
     * @var string
     */
    protected $base = 'https://api.instagram.com/v1/';

    /**
     * @var string | null
     */
    protected $header = null;

    /**
     * @var string | null
     */
    protected $queryParam = 'access_token';
}