<?php

use OAuth\OAuth2Service;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class OAuth2ServiceImplementation extends OAuth2Service
{
    /**
     * @var string | null
     */
    protected $header = null;

    /**
     * @var string
     */
    protected $queryParam = 'oauth_token';
}

class OAuth2ServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tear down method checks Mockery expectations
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function authorization_url()
    {
        // Arrange
        $service = new OAuth2Service;
        $options = ['foo' => 'bar'];
        $credentials = [
            'client_id' => 'cl13nt1d',
            'client_secret' => 'cl13nts3cr3t'
        ];
        $scopes = ['email', 'read', 'write'];
        $expectedQuery = [
            'foo' => 'bar',
            'client_id' => 'cl13nt1d',
            'redirect_uri' => '',
            'response_type' => 'code',
            'type' => 'web_server',
            'scope' => join(' ', $scopes),
        ];

        $service->setCredentials($credentials);
        $service->setScopes($scopes);

        // Act
        $authorizationUrl = $service->authorizationUrl($options);

        // Assert
        list($endpoint, $queryString) = explode('?', $authorizationUrl);
        parse_str($queryString, $query);
        $this->assertEquals('', $endpoint);
        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * Test the protected prepare method
     *
     * @test
     */
    public function prepare()
    {
        // Arrange
        $service = new OAuth2Service;
        $client = Mockery::mock('GuzzleHttp\Client');
        $response = Mockery::mock('GuzzleHttp\Message\Response');
        $token = ['access_token' => '4cc3st0k3n'];
        $endpoint = 'users/me';

        $client->shouldReceive('get')->once()
            ->with($endpoint)->andReturn($response);
        $client->shouldReceive('setDefaultOption')->once()
            ->with('headers/Authorization', 'OAuth '. $token['access_token'])
            ->andReturn($client);

        $service->setClient($client);
        $service->setToken($token);

        // Act
        $returned = $service->get($endpoint);

        // Assert
        $this->assertEquals($response, $returned);
    }

    /**
     * Test the protected prepare method when oauth provider requieres query param.
     *
     * @test
     */
    public function prepare_query_param()
    {
        // Arrange
        $service = new OAuth2ServiceImplementation;
        $client = Mockery::mock('GuzzleHttp\Client');
        $response = Mockery::mock('GuzzleHttp\Message\Response');
        $token = ['access_token' => '4cc3st0k3n'];
        $endpoint = 'users/me';

        $client->shouldReceive('get')->once()
            ->with($endpoint)->andReturn($response);
        $client->shouldReceive('setDefaultOption')->once()
            ->with('query', ['oauth_token' => $token['access_token']])
            ->andReturn($client);

        $service->setClient($client);
        $service->setToken($token);

        // Act
        $returned = $service->get($endpoint);

        // Assert
        $this->assertEquals($response, $returned);
    }

    /**
     * Test the access token method.
     *
     * @test
     */
    public function access_token()
    {
        // Arrange
        $service = new OAuth2Service;
        $code = 'c0d3';
        $accessToken = '4cc3st0k3n';
        $credentials = [
            'client_id' => 'cl13nt1d',
            'client_secret' => 'cl13nts3cr3t',
        ];
        $body = array_merge($credentials, [
            'code' => $code,
            'redirect_uri' => '',
            'grant_type' => 'authorization_code',
        ]);
        $responseBody = Stream::factory('{"access_token": "'. $accessToken .'"}');

        $client = new Client;
        $mock = new Mock([new Response(200, [], $responseBody)]);

        $client->getEmitter()->attach($mock);

        $service->setCredentials($credentials);
        $service->setClient($client);

        // Act
        $returned = $service->accessToken($code);

        // Assert
        $this->assertEquals(['access_token' => $accessToken], $returned);
    }

    /**
     * Test the access token method returns empty array when request fails.
     *
     * @test
     */
    public function access_token_empty()
    {
        // Arrange
        $service = new OAuth2Service;
        $code = 'c0d3';
        $credentials = [
            'client_id' => 'cl13nt1d',
            'client_secret' => 'cl13nts3cr3t',
        ];
        $body = array_merge($credentials, [
            'code' => $code,
            'redirect_uri' => '',
            'grant_type' => 'authorization_code',
        ]);
        $mock = new Mock([new Response(400)]);

        $client = new Client;
        $client->getEmitter()->attach($mock);

        $service->setCredentials($credentials);
        $service->setClient($client);

        // Act
        $returned = $service->accessToken($code);

        // Assert
        $this->assertEquals([], $returned);
    }
}
