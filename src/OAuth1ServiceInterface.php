<?php
namespace OAuth;

interface OAuth1ServiceInterface extends ServiceInterface
{
    /**
     * Set the oauth callback URL to be used in requestToken()
     *
     * @param  string  $callback
     * @return ServiceInterface
     */
    public function setOAuthCallback($callback);

    /**
     * Get the oauth callback URL to be used in requestToken()
     *
     * @return string
     */
    public function getOAuthCallback();

    /**
     * Request a request token.
     * 
     * @return array
     */
    public function requestToken();

    /**
     * Request the service an access token.
     * 
     * @param  string $oauthToken
     * @param  string $oauthVerifier
     * @return array
     */
    public function accessToken($oauthToken, $oauthVerifier);
}
