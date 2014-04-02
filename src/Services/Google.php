<?php
namespace OAuth\Services;

use OAuth\OAuth2Service;
use DateTime;

class Google extends OAuth2Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://accounts.google.com/o/oauth2/auth';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://accounts.google.com/o/oauth2/token';

    /**
     * @var string
     */
    protected $base = 'https://www.googleapis.com/';

    /**
     * Parsing the access token response.
     * 
     * @param  string $response
     * @return array
     */
    public function parseAccessToken($response)
    {
        $token = json_decode($response, true);
        $token['expires'] = new DateTime('now + '. $token['expires_in'] .' seconds');
        unset($token['expires_in']);

        return $token;
    }
}
