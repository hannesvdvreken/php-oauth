<?php
namespace OAuth;

use Guzzle\Http\Exception\ClientErrorResponseException;

class OAuth2Service extends Service implements OAuth2ServiceInterface
{
    /**
     * Can be one of 'OAuth', 'Bearer' or null.
     * 
     * @var string | null
     */
    protected $header = 'OAuth';

    /**
     * Can be one of 'access_token', 'oauth2_access_token', 'oauth_token', 'apikey' or whatever.
     * 
     * @var string | null
     */
    protected $queryParam = null;

    /**
     * Should be web_server, but some services don't accept the type parameter.
     *
     * @var string | null
     */
    protected $type = 'web_server';

    /**
     * Request the service an access token.
     *
     * @param  string $code
     * @return array
     */
    public function accessToken($code)
    {
        // Build the body.
        $body = array(
            'code'          => $code,
            'client_id'     => $this->credentials['client_id'],
            'client_secret' => $this->credentials['client_secret'],
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
        );

        // Make the request.
        try {
            $response = $this->client->post($this->endpointAccessToken, null, $body)->send(null)->getBody(true);
        } catch (ClientErrorResponseException $e) {
            return $this->token = array();
        }

        // Return the token.
        return $this->token = $this->parseAccessToken($response);
    }

    /**
     * Get the authorization url.
     *
     * @param  string  $options
     * @return string
     */
    public function authorizationUrl(array $options = array())
    {
        // Build list of query parameters
        $queryParams = array(
            'client_id'     => $this->credentials['client_id'],
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
        );

        // Add the default type parameter.
        if ($this->type) {
            $queryParams['type'] = $this->type;
        }

        // Add optional scopes parameter.
        if (! empty($this->scopes)) {
            $queryParams['scope'] = implode($this->scopeDelimiter, $this->scopes);
        }

        // Merge with the options.
        $queryParams = array_merge($options, $queryParams);

        // Concat and return.
        return $this->endpointAuthorization .'?'. http_build_query($queryParams);
    }

    /**
     * Parse the access token response
     *
     * @param  string $response
     * @return array
     */
    protected function parseAccessToken($response)
    {
        return json_decode($response, true);
    }

    /**
     * Prepare the client for a request.
     *
     * @return  Guzzle\Http\Client
     */
    protected function prepare()
    {
        if ($this->header) {
            $authorization = $this->header .' '. $this->token['access_token'];
            return $this->client->setDefaultOption('headers', array('Authorization' => $authorization));
        } elseif ($this->queryParam) {
            return $this->client->setDefaultOption('query', array($this->queryParam => $this->token['access_token']));
        }
    }
}
