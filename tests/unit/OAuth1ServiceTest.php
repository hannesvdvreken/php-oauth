<?php

use OAuth\OAuth1Service;

class OAuth1ServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Testing the request token request
     */
    public function test_request_token()
    {
        // Arrange
        $credentials = array(
            'client_id'     => 'client-id',
            'client_secret' => 'client-secret',
        );

        $response = array(
            'oauth_token' => 'oauth-token',
            'oauth_token_secret' => 'oauth-token-secret',
        );

        // Mock guzzle client
        $client = new Guzzle\Http\Client;
        $mock = new Guzzle\Plugin\Mock\MockPlugin;
        $mock->addResponse(new Guzzle\Http\Message\Response(200, null, http_build_query($response)));
        $client->addSubscriber($mock);

        // Act
        $service = new OAuth1Service($client);
        $service->setCredentials($credentials);
        $token = $service->requestToken();
        
        // Assert
        $this->assertEquals($response, $token);
    }

    /**
     * Test that the service caches the oauth_token_secret
     */
    public function test_request_token_is_cached()
    {
        // Arrange
        $token = array(
            'oauth_token'        => 'oauth-token',
            'oauth_token_secret' => 'oauth-token-secret',
        );

        // Act
        $service = new OAuth1Service(new Guzzle\Http\Client);
        $service->setToken($token);
        $returned = $service->requestToken();

        // Assert
        $this->assertEquals($token, $returned);
    }
    
    /**
     * The authorization to which we need to redirect the user.
     */
    public function test_authorization_url()
    {
        // Arrange
        $token = array(
            'oauth_token'        => $oauth_token = 'oauth-token',
            'oauth_token_secret' => 'oauth-token-secret',
        );
        $state = 'csrf-state';
        $scope = implode(' ', $scopes = array('scope-1', 'scope-2'));

        // Combine
        $expected = '?' . http_build_query(compact('state', 'oauth_token', 'scope'));

        // Act
        $service = new OAuth1Service(new Guzzle\Http\Client);
        $service->setScopes($scopes);
        $service->setToken($token);
        $url = $service->authorizationUrl(compact('state'));

        // Assert
        $this->assertEquals($expected, $url);
    }

    /**
     * 
     */
    public function test_access_token()
    {
        // Arrange
        $oauthToken = 'oauth-token';
        $oauthVerifier = 'oauth-verifier';
        $token = array(
            'oauth_token' => $oauthToken,
            'oauth_token_secret' => 'oauth-token-secret',
        );
        $credentials = array(
            'client_id'     => 'client-id',
            'client_secret' => 'client-secret',
        );

        // Mock guzzle client
        $client = new Guzzle\Http\Client;
        $mock = new Guzzle\Plugin\Mock\MockPlugin;
        $mock->addResponse(new Guzzle\Http\Message\Response(200, null, http_build_query($token)));
        $client->addSubscriber($mock);

        // Act
        $service = new OAuth1Service($client);
        $service->setCredentials($credentials);
        $service->setToken($token);
        $result = $service->accessToken($oauthToken, $oauthVerifier);
        
        // Assert
        $this->assertEquals($token, $result);
    }
}