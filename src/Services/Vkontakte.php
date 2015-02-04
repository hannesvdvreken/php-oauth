<?php
namespace OAuth\Services;

use OAuth\OAuth2Service;

class Vkontakte extends OAuth2Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://oauth.vk.com/authorize';

    /**
     * @var string
     */
    protected $endpointAccessToken = 'https://oauth.vk.com/access_token';

    /**
     * @var string
     */
    protected $base = 'https://api.vk.com/method/';

    /**
     * @var string
     */
    protected $scopeDelimiter = ',';

    /**
     * @var null
     */
    protected $header = null;

    /**
     * @var string
     */
    protected $queryParam = 'access_token';
}
