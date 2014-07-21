<?php
namespace OAuth\Services;

use DateTime;
use OAuth\OAuth2Service;

class Linkedin extends OAuth2Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://www.linkedin.com/uas/oauth2/authorization';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://www.linkedin.com/uas/oauth2/accessToken';

    /**
     * @var string
     */
    protected $base = 'https://api.linkedin.com/v1/';

    /**
     * @var string
     */
    protected $header = null;

    /**
     * @var string
     */
    protected $queryParam = 'oauth2_access_token';

    /**
     * Parsing access token response
     *
     * @param  string $response
     * @return string
     */
    public function parseAccessToken($response)
    {
        // JSON decode the response.
        $token = json_decode($response, true);

        // Create a DateTime field.
        return [
            'access_token' => $token['access_token'],
            'expires' => new DateTime('now +'. $token['expires_in']. ' seconds'),
        ];
    }
}
