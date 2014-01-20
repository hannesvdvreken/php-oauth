<?php
namespace OAuth\Services;

use OAuth\OAuth2Service;

class Github extends OAuth2Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://github.com/login/oauth/authorize';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://github.com/login/oauth/access_token';

    /**
     * @var string
     */
    protected $base = 'https://api.github.com/';

    /**
     * Can be one of 'OAuth', 'Bearer' or null.
     * 
     * @var string | null
     */
    protected $header = 'Bearer';

    /**
     * Parsing access token response
     * 
     * @param  string $response
     * @return array
     */
    public function parseAccessToken($response)
    {
        parse_str($response, $data);

        return $this->token = $data;
    }
}