<?php
namespace OAuth\Services;

use OAuth\OAuth2Service;

class Dropbox extends OAuth2Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://www.dropbox.com/1/oauth2/authorize';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://api.dropbox.com/1/oauth2/token';

    /**
     * @var string
     */
    protected $base = 'https://api.dropbox.com/1/';

    /**
     * Can be one of 'OAuth', 'Bearer' or null.
     * 
     * @var string | null
     */
    protected $header = 'Bearer';

    /**
     * @var string | null
     */
    protected $type = null;
}
