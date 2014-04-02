<?php
namespace OAuth\Services;

use OAuth\OAuth2Service;
use DateTime;

class Mailchimp extends OAuth2Service
{
    /**
     * @var string
     */
    protected $dc;

    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://login.mailchimp.com/oauth2/authorize';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://login.mailchimp.com/oauth2/token';

    /**
     * @var string
     */
    protected $base = '';

    /**
     * @var string | null
     */
    protected $header = null;

    /**
     * @var string | null
     */
    protected $queryParam = 'apikey';

    /**
     * Set DC.
     *
     * @param  string $dc
     * @return OAuth\Service\Mailchimp
     */
    public function setDc($dc)
    {
        $this->dc = $dc;
        return $this;
    }

    /**
     * Get DC.
     *
     * @return string
     */
    public function getDc()
    {
        return $this->dc;
    }

    /**
     * Parsing access token response
     * 
     * @param  string $response
     * @return array
     */
    public function parseAccessToken($response)
    {
        // Get from json format
        $this->token = json_decode($response, true);

        // Retrieve the DC for this token.
        $this->dc = $this->getDatacenter();

        // Return the token.
        return $this->token;
    }

    /**
     * Prepare the client for a request.
     *
     * @return  Guzzle\Http\Client
     */
    public function prepare()
    {
        // If the dc is not know, do a request.
        if (is_null($this->dc)) {
            $this->dc = $this->getDatacenter();
        }

        // Set the base url.
        $this->base = 'https://'. $this->dc .'.api.mailchimp.com/2.0/';
        $this->client->setBaseUrl($this->base);

        // Let the parent method do it's job.
        return parent::prepare();
    }

    /**
     * Get the DC for the access token.
     *
     * @param  array  $token
     * @return string
     */
    public function getDatacenter($token = array())
    {
        // Use stored access token as fallback.
        $accessToken = isset($token['access_token']) ? $token['access_token'] : $this->token['access_token'];
        
        // Build url.
        $endpoint = 'https://login.mailchimp.com/oauth2/metadata?oauth_token='. $accessToken;

        // Perform the request.
        $response = $this->client->get($endpoint)->send(null)->json();

        // Grab the data.
        return $response['dc'];
    }
}
