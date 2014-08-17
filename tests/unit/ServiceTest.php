<?php

use OAuth\Service;
use GuzzleHttp\Client;

class ServiceImplementation extends Service
{
    /**
     * @var string
     */
    protected $endpointAuthorization = 'https://service.com/oauth/dialog';
}

class ServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * After every test, check mockery mocks expectations.
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Tests the client setter and getter.
     *
     * @test
     */
    public function client_setter_and_getter()
    {
        // Arrange
        $service = new Service;
        $client = new Client;

        // Act
        $returned = $service->setClient($client)->getClient();

        // Assert
        $this->assertEquals($client, $returned);
    }

    /**
     * Test the service getter and setter for credentials.
     *
     * @test
     */
    public function credentials_setter_and_getter()
    {
        // Arrange
        $service = new Service;
        $credentials = [
            'client_id' => 'client-id',
            'client_secret' => 'client-secret'
        ];

        // Act
        $returned = $service->setCredentials($credentials)->getCredentials();

        // Assert
        $this->assertEquals($credentials, $returned);
    }

    /**
     * Test the service returns the same credentials as provided in the constructor.
     *
     * @test
     */
    public function credentials_via_constructor()
    {
        // Arrange
        $credentials = [
            'client_id' => 'client-id',
            'client_secret' => 'client-secret'
        ];

        // Act
        $returned = (new Service(null, $credentials))->getCredentials();

        // Assert
        $this->assertEquals($credentials, $returned);
    }

    /**
     * Test the service has setters and getters for redirect_uri.
     *
     * @test
     */
    public function redirect_uri_setter_and_getter()
    {
        // Arrange
        $service = new Service;
        $redirectUri = 'https://example.org/callback';

        // Act
        $returned = $service->setRedirectUri($redirectUri)->getRedirectUri();

        // Assert
        $this->assertEquals($redirectUri, $returned);
    }

    /**
     * Test the service returns the redirect_uri injected via constructor.
     *
     * @test
     */
    public function redirect_uri_via_constructor()
    {
        // Arrange
        $redirectUri = 'https://example.org/callback';

        // Act
        $returned = (new Service($redirectUri))->getRedirectUri();

        // Assert
        $this->assertEquals($redirectUri, $returned);
    }

    /**
     * Test the scopes attribute has setter and getter.
     *
     * @test
     */
    public function scopes_setter_and_getter()
    {
        // Arrange
        $service = new Service;
        $scopes = ['email', 'read', 'write'];

        // Act
        $returned = $service->setScopes($scopes)->getScopes();

        // Assert
        $this->assertEquals($scopes, $returned);
    }

    /**
     * Test the scopes getter returns what is injected via the constructor.
     *
     * @test
     */
    public function scopes_via_constructor()
    {
        // Arrange
        $scopes = ['email', 'read', 'write'];

        // Act
        $returned = (new Service(null, [], $scopes))->getScopes();

        // Assert
        $this->assertEquals($scopes, $returned);
    }

    /**
     * Test the token attribute has setter and getter.
     *
     * @test
     */
    public function token_setter_and_getter()
    {
        // Arrange
        $service = new Service;
        $token = ['access_token' => '0123456789abcdef'];

        // Act
        $returned = $service->setToken($token)->getToken();

        // Assert
        $this->assertEquals($token, $returned);
    }

    /**
     * Test the token getter returns what is injected via the constructor.
     *
     * @test
     */
    public function token_via_constructor()
    {
        // Arrange
        $token = ['email', 'read', 'write'];

        // Act
        $returned = (new Service(null, [], [], $token))->getToken();

        // Assert
        $this->assertEquals($token, $returned);
    }

    /**
     * Test the authorizationUrl returns the authorizationUrl attribute.
     *
     * @test
     */
    public function authorization_url()
    {
        // Arrange
        $service = new ServiceImplementation();

        // Act
        $authorizationUrl = $service->authorizationUrl();

        // Assert
        $this->assertEquals('https://service.com/oauth/dialog', $authorizationUrl);
    }

    /**
     * Test that calls are proxied to the guzzle client.
     *
     * @test
     */
    public function calls_are_proxied()
    {
        // Arrange
        $service = new Service;
        $methods = array('get', 'put', 'post', 'patch', 'delete', 'head');
        $url = 'users/me';
        $client = Mockery::mock('GuzzleHttp\Client');
        $response = Mockery::mock('GuzzleHttp\Message\Response');

        foreach ($methods as $method) {
            $client->shouldReceive($method)->once()
                ->with($url)->andReturn($response);
            $service->setClient($client);

            // Act
            $result = $service->$method($url);

            // Assert
            $this->assertEquals($response, $result);
        }
    }

    /**
     * Test that calls are not proxied if not valid.
     *
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Method [nonExistingHttpVerb] does not exist
     * @test
     */
    public function invalid_calls_are_not_proxied()
    {
        // Arrange
        $method = 'nonExistingHttpVerb';
        $url = 'users/me';
        $client = Mockery::mock('GuzzleHttp\Client');
        $service = (new Service)->setClient($client);

        // Act
        $result = $service->$method($url);

        // Assert
    }
}
