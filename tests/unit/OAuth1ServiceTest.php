<?php

use OAuth\OAuth1Service;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;

class OAuth1ServiceImplementation extends OAuth1Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://service.com/oauth/dialog';
}

class OAuth1ServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tear down method checks mockery expectations
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Test the request token method.
     *
     * @test
     */
    public function oauth_callback_setter_and_getter()
    {
        // Arrange
        $service = new OAuth1Service;
        $callbackUrl = 'http://callback-url.org/arrive';

        //Act
        $returned = $service->setOAuthCallback($callbackUrl)->getOAuthCallback();

        //Assert
        $this->assertEquals($callbackUrl, $returned);
    }

    /**
     * Test the request token method with the optional callback url.
     *
     * @test
     */
    public function request_token_custom_callback()
    {
        // Arrange
        $service = new OAuth1Service;
        $credentials = [
            'client_id' => 'cl13nt1d',
            'client_secret' => 'cl13nts3cr3t',
        ];
        $body = [
            'oauth_token' => 'O4thtOk3n',
            'oauth_token_secret' => '04ths3cr3t',
        ];
        $custom_callback = 'http://callback-url.org/arrive';
        $client = new Client;
        $responseBody = Stream::factory(http_build_query($body));
        $mock = new Mock([new Response(200, [], $responseBody)]);
        $client->getEmitter()->attach($mock);

        $service->setCredentials($credentials);
        $service->setClient($client);
        $service->setOAuthCallback($custom_callback);

        // Act
        $returned = $service->requestToken();

        // Assert
        $this->assertEquals($body, $returned);
    }

    /**
     * Test the request token method.
     *
     * @test
     */
    public function request_token()
    {
        // Arrange
        $service = new OAuth1Service;
        $credentials = [
            'client_id' => 'cl13nt1d',
            'client_secret' => 'cl13nts3cr3t',
        ];
        $body = [
            'oauth_token' => 'O4thtOk3n',
            'oauth_token_secret' => '04ths3cr3t',
        ];
        $client = new Client;
        $responseBody = Stream::factory(http_build_query($body));
        $mock = new Mock([new Response(200, [], $responseBody)]);
        $client->getEmitter()->attach($mock);

        $service->setCredentials($credentials);
        $service->setClient($client);

        // Act
        $returned = $service->requestToken();

        // Assert
        $this->assertEquals($body, $returned);
    }

    /**
     * Test the request token binds a oauth subscriber to the client.
     *
     * @test
     */
    public function request_token_uses_oauth1_subscriber()
    {
        // Arrange
        $service = new OAuth1Service;
        $credentials = [
            'client_id' => 'cl13nt1d',
            'client_secret' => 'cl13nts3cr3t',
        ];
        $body = [
            'oauth_token' => 'O4thtOk3n',
            'oauth_token_secret' => '04ths3cr3t',
        ];

        $client = Mockery::mock('GuzzleHttp\Client');
        $client->shouldReceive('getEmitter->attach')->once()
            ->with(Mockery::type('GuzzleHttp\Subscriber\Oauth\Oauth1'));
        $client->shouldReceive('post')->once()
            ->with('', ['auth' => 'oauth'])->andReturn($client);
        $client->shouldReceive('getBody')->once()
            ->with()->andReturn(http_build_query($body));

        $service->setCredentials($credentials);
        $service->setClient($client);

        // Act
        $returned = $service->requestToken();

        // Assert
        $this->assertEquals($body, $returned);
    }

    /**
     * Test that the request token method does not make a second request.
     * @test
     */
    public function request_token_cached()
    {
        // Arrange
        $service = new OAuth1Service;
        $requestToken = [
            'oauth_token' => 'O4thtOk3n',
            'oauth_token_secret' => '04ths3cr3t',
        ];
        $client = Mockery::mock('GuzzleHttp\Client');

        $service->setClient($client);
        $service->setToken($requestToken);

        // Act
        $returned = $service->requestToken();

        // Assert
        $this->assertEquals($requestToken, $returned);
    }

    /**
     * Test the request token returns empty array when request fails.
     *
     * @test
     */
    public function request_token_fail_returns_empty_array()
    {
        // Arrange
        $service = new OAuth1Service;
        $credentials = [
            'client_id' => 'cl13nt1d',
            'client_secret' => 'cl13nts3cr3t',
        ];
        $client = new Client;
        $mock = new Mock([new Response(400)]);
        $client->getEmitter()->attach($mock);

        $service->setCredentials($credentials);
        $service->setClient($client);

        // Act
        $returned = $service->requestToken();

        // Assert
        $this->assertEmpty($returned);
    }

    /**
     * Test the access token request method.
     * @test
     */
    public function access_token()
    {
        // Arrange
        $service = new OAuth1Service;
        $oauthToken = 'tOk3n';
        $oauthVerifier = 'v3r1f13r';
        $credentials = [
            'client_id' => 'cl13nt1d',
            'client_secret' => 'cl13nt1d',
        ];
        $token = [
            'oauth_token_secret' => '04tht0k3ns3cr3t',
        ];

        $client = new Client;
        $body = Stream::factory(http_build_query($token));
        $mock = new Mock([new Response(200, [], $body)]);
        $client->getEmitter()->attach($mock);

        $service->setCredentials($credentials);
        $service->setToken($token);
        $service->setClient($client);

        // Act
        $returned = $service->accessToken($oauthToken, $oauthVerifier);

        // Assert
        $this->assertEquals($token, $returned);
    }

    /**
     * Test the access token request binds a oauth subscriber to the client.
     *
     * @test
     */
    public function access_token_uses_oauth1_subscriber()
    {
        // Arrange
        $service = new OAuth1Service;
        $token = 'tOk3n';
        $verifier = 'v3r1f13r';
        $credentials = [
            'client_id' => 'cl13nt1d',
            'client_secret' => 'cl13nt1d',
        ];
        $token = [
            'oauth_token_secret' => '04tht0k3ns3cr3t',
        ];
        $body = [
            'oauth_verifier' => $verifier,
        ];

        $client = Mockery::mock('GuzzleHttp\Client');
        $client->shouldReceive('getEmitter->attach')->once()
            ->with(Mockery::type('GuzzleHttp\Subscriber\Oauth\Oauth1'));
        $client->shouldReceive('post')->once()
            ->with('', ['body' => $body, 'auth' => 'oauth'])->andReturn($client);
        $client->shouldReceive('getBody')->once()
            ->with()->andReturn(http_build_query($token));

        $service->setCredentials($credentials);
        $service->setToken($token);
        $service->setClient($client);

        // Act
        $returned = $service->accessToken($token, $verifier);

        // Assert
        $this->assertEquals($token, $returned);
    }

    /**
     * Test authorizationUrl method.
     *
     * @test
     */
    public function authorization_url()
    {
        // Arrange
        $service = new OAuth1ServiceImplementation;
        $options = ['foo' => 'bar'];
        $scopes = ['email', 'profile'];
        $token = ['oauth_token' => '04tht0k3n', 'oauth_token_secret' => '04tht0k3ns3cr3t'];
        $expected = 'https://service.com/oauth/dialog?foo=bar&oauth_token=04tht0k3n&scope=email+profile';

        $service->setScopes($scopes);
        $service->setToken($token);

        // Act
        $returned = $service->authorizationUrl($options);

        // Assert
        $this->assertEquals($expected, $returned);
    }

    /**
     * Test the prepare method via a proxied call.
     *
     * @test
     */
    public function prepare()
    {
        // Arrange
        $service = new OAuth1Service;
        $endpoint = 'users/me';
        $credentials = ['client_id' => 'cl13nt1d', 'client_secret' => 'cl13nts3cr3t'];
        $token = ['oauth_token' => '04tht0k3n', 'oauth_token_secret' => '04tht0k3ns3cr3t'];

        $client = Mockery::mock('GuzzleHttp\Client');
        $client->shouldReceive('get')->once()
            ->with($endpoint)->andReturn($client);
        $client->shouldReceive('getEmitter->attach')->once()
            ->with(Mockery::type('GuzzleHttp\Subscriber\Oauth\Oauth1'));
        $client->shouldReceive('setDefaultOption')->once()
            ->with('auth', 'oauth');

        $service->setClient($client);
        $service->setCredentials($credentials);
        $service->setToken($token);

        // Act
        $returned = $service->get($endpoint);

        // Assert
        $this->assertEquals($client, $returned);
    }
}
