<?php
namespace OAuth;

use GuzzleHttp\Subscriber\Oauth\Oauth1;
use GuzzleHttp\Exception\RequestException;

class OAuth1Service extends Service implements OAuth1ServiceInterface
{
    /**
     * @var string
     */
    protected $endpointRequestToken = '';

    /**
     * @var string
     */
    protected $oauthCallback = '';

    /**
     * Set the oauth callback URL to be used in requestToken()
     *
     * @param  string  $callback
     * @return ServiceInterface
     */
    public function setOAuthCallback($callback)
    {
        $this->oauthCallback = $callback;
        return $this;
    }

    /**
     * Get the oauth callback URL to be used in requestToken()
     *
     * @return string
     */
    public function getOAuthCallback()
    {
        return $this->oauthCallback;
    }

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

        $subscriber = new Oauth1([
            'consumer_key' => $this->credentials['client_id'],
            'consumer_secret' => $this->credentials['client_secret'],
            'callback' => $this->oauthCallback !== '' ? $this->oauthCallback : null,
        ]);

        $this->client->getEmitter()->attach($subscriber);

        try {
            $response = $this->client->post($this->endpointRequestToken, ['auth' => 'oauth'])->getBody();
        } catch (RequestException $rex) {
            return [];
        }

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
        $subscriber = new Oauth1([
            'consumer_key'    => $this->credentials['client_id'],
            'consumer_secret' => $this->credentials['client_secret'],
            'token'           => $oauthToken,
        ]);

        $body = ['oauth_verifier' => $oauthVerifier];

        $this->client->getEmitter()->attach($subscriber);
        $response = $this->client
            ->post($this->endpointAccessToken, ['body' => $body, 'auth' => 'oauth'])
            ->getBody();

        $data = $this->parseAccessToken($response);
        $keys = ['oauth_token', 'oauth_token_secret'];

        // array_only
        $this->token = array_intersect_key($data, array_flip((array) $keys));

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
    public function authorizationUrl(array $options = [])
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
     * @return  GuzzleHttp\Client
     */
    protected function prepare()
    {
        // Create an OAuth1 Subscriber for Guzzle.
        $subscriber = new Oauth1([
            'consumer_key'    => $this->credentials['client_id'],
            'consumer_secret' => $this->credentials['client_secret'],
            'token'           => $this->token['oauth_token'],
            'token_secret'    => $this->token['oauth_token_secret'],
        ]);

        // Assign it and return the client itself.
        $this->client->getEmitter()->attach($subscriber);

        // Enable the subscriber for all requests.
        $this->client->setDefaultOption('auth', 'oauth');

        return $this->client;
    }
}
