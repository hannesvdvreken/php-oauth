<?php
namespace OAuth\Services;

use DateTime;
use OAuth\OAuth2Service;

class Facebook extends OAuth2Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://www.facebook.com/dialog/oauth';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://graph.facebook.com/oauth/access_token';

    /**
     * @var string
     */
    protected $base = 'https://graph.facebook.com';

    /**
     * @var string
     */
    protected $scopeDelimiter = ',';

    /**
     * Parsing access token response
     *
     * @param string $response
     * @return string
     */
    public function parseAccessToken($response)
    {
        parse_str($response, $data);

        $data['expires'] = new DateTime('now +'. $data['expires']. ' seconds');

        return $data;
    }
}
