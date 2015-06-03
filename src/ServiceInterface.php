<?php
namespace OAuth;

interface ServiceInterface
{
    /**
     * Get the authorization url.
     *
     * @param array $options
     * @return string
     */
    public function authorizationUrl(array $options = []);

    /**
     * Set token
     *
     * @param array $token
     * @return \OAuth\ServiceInterface
     */
    public function setToken(array $token);

    /**
     * Get token
     *
     * @return array
     */
    public function getToken();

    /**
     * Set credentials
     *
     * @param array $credentials
     * @return \OAuth\ServiceInterface
     */
    public function setCredentials(array $credentials);

    /**
     * Set scope
     *
     * @param array $scopes
     * @return \OAuth\ServiceInterface
     */
    public function setScopes(array $scopes);

    /**
     * Get scope
     *
     * @return array
     */
    public function getScopes();

    /**
     * Set redirect uri
     *
     * @param string $redirectUri
     * @return \OAuth\ServiceInterface
     */
    public function setRedirectUri($redirectUri);

    /**
     * Get redirect uri
     *
     * @return string
     */
    public function getRedirectUri();
}
