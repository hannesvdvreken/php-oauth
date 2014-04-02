<?php
namespace OAuth\Services;

use OAuth\OAuth1Service;

class Twitter extends OAuth1Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://api.twitter.com/oauth/authenticate';

    /**
     * @var string
     */
    protected $endpointRequestToken = 'https://api.twitter.com/oauth/request_token';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://api.twitter.com/oauth/access_token';

    /**
     * @var string
     */
    protected $base = 'https://api.twitter.com/1.1/';
}
