<?php
namespace OAuth;

use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Http\Exception\ClientErrorResponseException;

class OAuth1Service extends Service implements OAuth1ServiceInterface
{
    /**
     * @var string
     */
    protected $endpointRequestToken = '';

    /**
     * Request the service a request token.
     *
     * @return array
     */
    public function requestToken()
    {
        // Don't do this request twice. Let the user choose to call this before autorizeUrl
        if (isset($this->token['oauth_token_secret'])) {
            return $this->token;
        }

        $plugin = new OauthPlugin(array(
            'consumer_key' => $this->credentials['client_id'],
            'consumer_secret' => $this->credentials['client_secret'],
        ));

        $response = $this->client->addSubscriber($plugin)->post($this->endpointRequestToken)->send(null)->getBody(true);

        return $this->token = $this->parseRequestToken($response);
    }

    /**
     * Parse the request token.
     *
     * @param  string $response
     * @return array
     */
    protected function parseRequestToken($response)
    {
        parse_str($response, $data);
        return $data;
    }

    /**
     * Request the service an access token.
     *
     * @param  string $oauthToken
     * @param  string $oauthVerifier
     * @return array
     */
    public function accessToken($oauthToken, $oauthVerifier)
    {
        $plugin = new OauthPlugin(array(
            'consumer_key'    => $this->credentials['client_id'],
            'consumer_secret' => $this->credentials['client_secret'],
            'token'           => $oauthToken,
            'token_secret'    => $this->token['oauth_token_secret'],
        ));

        $postData = array('oauth_verifier' => $oauthVerifier);

        $request = $this->client->addSubscriber($plugin)->post($this->endpointAccessToken, null, $postData);
        $response = $request->send(null)->getBody(true);

        $this->token = array_only(
            $data = $this->parseAccessToken($response),
            array('oauth_token', 'oauth_token_secret')
        );

        return $data;
    }

    /**
     * Parse the access token.
     *
     * @param  string $response
     * @return array
     */
    protected function parseAccessToken($response)
    {
        parse_str($response, $data);
        return $data;
    }

    /**
     * Get the authorization url.
     *
     * @param  string  $options
     * @return string
     */
    public function authorizationUrl(array $options = array())
    {
        // Request the initial request token.
        extract($this->requestToken());

        // Build list of query parameters.
        $queryParams = compact('oauth_token');

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
     * Prepare the client for a request.
     *
     * @return  Guzzle\Http\Client
     */
    protected function prepare()
    {
        // Create an OAuth Plugin for Guzzle.
        $plugin = new OauthPlugin(array(
            'consumer_key'    => $this->credentials['client_id'],
            'consumer_secret' => $this->credentials['client_secret'],
            'token'           => $this->token['oauth_token'],
            'token_secret'    => $this->token['oauth_token_secret'],
        ));

        // Assign it and return the client itself.
        return $this->client->addSubscriber($plugin);
    }
}
