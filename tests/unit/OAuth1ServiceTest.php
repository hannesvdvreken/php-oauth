<?php

require_once(__DIR__ .'/OAuthServiceTest.php');
use OAuth\OAuth1Service;

class OAuth1ServiceTest extends OAuthServiceTest
{
    /**
     * Teardown function
     */
    public function tearDown()
    {
        Mockery::close();
    }

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
        $requestTokenEndpoint = '';
        $body = 'oauth_token=oauth-token&oauth_token_secret=oauth-token-secret';
        parse_str($body, $expected);
        $me = $this;

        // Mock guzzle client
        $client = $this->mockGuzzle();
        $client->shouldReceive('addSubscriber')->once()->with(Mockery::on(function($plugin) use ($me)
        {
            $me->assertInstanceOf('Guzzle\Plugin\Oauth\OauthPlugin', $plugin);
            return true;
        }))->andReturn(Mockery::self());
        $client->shouldReceive('post')->once()->with($requestTokenEndpoint, null, null)->andReturn(Mockery::self());

        // Mock response
        $client->shouldReceive('send')->once()->with()->andReturn($this->mockGuzzleResponse($body));

        // Act
        $service = new OAuth1Service($client);
        $service->setCredentials($credentials);
        $token = $service->requestToken();
        
        // Assert
        $this->assertEquals($expected, $token);
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
        $service = new OAuth1Service($this->mockGuzzle());
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
        $scopes = array('scope-1', 'scope-2');
        $token = array(
            'oauth_token'        => $oauth_token = 'oauth-token',
            'oauth_token_secret' => 'oauth-token-secret',
        );
        $options = array('state' => 'csrf-state');

        $expected = '?' . http_build_query(array_merge($options, compact('oauth_token'), array('scope' => implode(' ', $scopes))));

        // Act
        $service = new OAuth1Service($this->mockGuzzle());
        $service->setScopes($scopes);
        $service->setToken($token);
        $url = $service->authorizationUrl($options);

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
        $accessTokenEndpoint = '';
        $post = array('oauth_verifier' => $oauthVerifier);

        $body = 'access_token=access-token&key=value';
        parse_str($body, $expected);
        $me = $this;

        // Mock guzzle client
        $client = $this->mockGuzzle();
        $client->shouldReceive('addSubscriber')->once()->with(Mockery::on(function($plugin) use ($me)
        {
            $me->assertInstanceOf('Guzzle\Plugin\Oauth\OauthPlugin', $plugin);
            return true;
        }))->andReturn(Mockery::self());
        $client->shouldReceive('post')->once()->with($accessTokenEndpoint, null, $post)->andReturn(Mockery::self());

        // Mock response
        $client->shouldReceive('send')->once()->with()->andReturn($this->mockGuzzleResponse($body));

        // Act
        $service = new OAuth1Service($client);
        $service->setCredentials($credentials);
        $service->setToken($token);
        $token = $service->accessToken($oauthToken, $oauthVerifier);
        
        // Assert
        $this->assertEquals($expected, $token);
    }
}