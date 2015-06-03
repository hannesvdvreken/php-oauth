<?php
namespace OAuth\Services;

use DateTime;
use GuzzleHttp\ClientInterface;
use OAuth\OAuth2Service;

class Stackexchange extends OAuth2Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://stackexchange.com/oauth';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://stackexchange.com/oauth/access_token';

    /**
     * @var string
     */
    protected $base = 'http://api.stackexchange.com/2.2/';

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

        if ($data['expires']) {
            $data['expires'] = new DateTime('now +'. $data['expires']. ' seconds');
        }

        return $data;
    }

    /**
     * Override setClient method.
     *
     * @param \GuzzleHttp\ClientInterface $client
     * @return \OAuth\Service
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        $this->client->setDefaultOption('headers/Accept-Encoding', 'gzip');
        return $this;
    }
}
