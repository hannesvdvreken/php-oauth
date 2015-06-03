<?php
namespace OAuth;

interface OAuth2ServiceInterface extends ServiceInterface
{
    /**
     * Request the service an access token.
     * 
     * @param string $code
     * @return array
     */
    public function accessToken($code);
}
