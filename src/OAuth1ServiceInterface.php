<?php
namespace OAuth;

interface OAuth1ServiceInterface extends ServiceInterface
{
    /**
     * Request a request token.
     *
     * @return array
     */
    public function requestToken();

    /**
     * Request the service an access token.
     *
     * @param string $oauthToken
     * @param string $oauthVerifier
     * @return array
     */
    public function accessToken($oauthToken, $oauthVerifier);
}
