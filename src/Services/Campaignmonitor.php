<?php
namespace OAuth\Services;

use DateTime;
use OAuth\OAuth2Service;

class Campaignmonitor extends OAuth2Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://api.createsend.com/oauth';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://api.createsend.com/oauth/token';

    /**
     * @var string
     */
    protected $base = 'https://api.createsend.com/api/v3.1/';

    /**
     * @var string
     */
    protected $scopeDelimiter = ',';

    /**
     * Can be one of 'OAuth', 'Bearer' or null.
     *
     * @var string|null
     */
    protected $header = 'Bearer';

    /**
     * Parsing access token response
     *
     * @param string $response
     * @return array
     */
    public function parseAccessToken($response)
    {
        $token = json_decode($response, true);

        return [
            'access_token' => $token['access_token'],
            'refresh_token' => $token['refresh_token'],
            'expires' => new DateTime('now + '. $token['expires_in'] .' seconds'),
        ];
    }
}
