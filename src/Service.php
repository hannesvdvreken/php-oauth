<?php
namespace OAuth;

use BadMethodCallException;
use GuzzleHttp\ClientInterface;

class Service implements ServiceInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var array
     */
    protected $credentials;

    /**
     * @var array
     */
    protected $scopes;

    /**
     * @var array
     */
    protected $token;

    /**
     * @var string
     */
    protected $redirectUri = '';

    /**
     * @var string
     */
    protected $scopeDelimiter = ' ';

    /**
     * @var string
     */
    protected $endpointAuthorization = '';

    /**
     * @var string
     */
    protected $endpointAccessToken = '';

    /**
     * @var string
     */
    protected $base = '';

    /**
     * Constructor with the possibility to set the Guzzle client.
     *
     * @param string $redirectUri
     * @param array $credentials
     * @param array $scopes
     * @param array $token
     */
    public function __construct(
        $redirectUri = '',
        array $credentials = [],
        array $scopes = [],
        array $token = []
    ) {
        $this->setClient(new Client(['base_url' => $this->base]));
        $this->redirectUri = $redirectUri;
        $this->credentials = $credentials;
        $this->scopes = $scopes;
        $this->token = $token;
    }

    /**
     * Swap out the internal Guzzle client.
     *
     * @param \GuzzleHttp\ClientInterface $client
     * @return \OAuth\ServiceInterface
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get the internal Guzzle client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get the authorization url.
     * Needs to be overwritten to add parameters.
     *
     * @param array $options
     * @return string
     */
    public function authorizationUrl(array $options = [])
    {
        return $this->endpointAuthorization;
    }

    /**
     * Set token
     *
     * @param array $token
     * @return \OAuth\ServiceInterface
     */
    public function setToken(array $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token
     *
     * @return array
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set credentials
     *
     * @param array $credentials
     * @return \OAuth\ServiceInterface
     */
    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * Get credentials
     *
     * @return array $credentials
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Set scope
     *
     * @param array $scopes
     * @return \OAuth\ServiceInterface
     */
    public function setScopes(array $scopes)
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * Get scope
     *
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Set redirect uri
     *
     * @param string $redirectUri
     * @return \OAuth\ServiceInterface
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    /**
     * Get redirect uri
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * Dynamically pass calls to the client.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (! in_array($method, array('get', 'post', 'put', 'patch', 'delete', 'head'))) {
            throw new BadMethodCallException('Method ['. $method .'] does not exist.');
        }

        $callable = array($this->prepare(), $method);

        return call_user_func_array($callable, $parameters);
    }

    /**
     * Prepare the client for a request.
     * Needs to be overwritten to configure the client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    protected function prepare()
    {
        return $this->client;
    }
}
