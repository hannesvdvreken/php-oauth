<?php

class ServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test that the method calls are proxied.
     */
    public function test_calls_are_proxied()
    {
        // Arrange
        $methods = array('get', 'put', 'post', 'patch', 'delete', 'head');
        $url = 'https://example.com/';
        $client = Mockery::mock('Guzzle\Http\Client');
        $response = Mockery::mock('Guzzle\Http\Message\Response');
        $client->shouldReceive('setBaseUrl')->times(count($methods))
            ->with('')->andReturn($client);

        foreach ($methods as $method)
        {
            $client->shouldReceive($method)->once()
                ->with($url)->andReturn($response);

            // Act
            $service = new OAuth\Service($client);
            $result = $service->$method($url);

            // Assert
            $this->assertEquals($response, $result);
        }
    }

    /**
     * Test that the constructor properly sets all parameters to attributes.
     */
    public function test_constructor()
    {
        // Arrange
        $client = new Guzzle\Http\Client;
        $redirectUri = 'http://example.com/oauth/callback';
        $credentials = array('client_id' => 'id', 'client_secret' => 'secret',);
        $scopes = array('read_email', 'write',);
        $scopes = array('read_email', 'write',);
        $token = array('access_token' => '****', 'expires' => new DateTime,);

        // Act
        $service = new OAuth\Service($client, $redirectUri, $credentials, $scopes, $token);

        // Assert
        $this->assertEquals($client, $service->getClient());
        $this->assertEquals($redirectUri, $service->getRedirectUri());
        $this->assertEquals($credentials, $service->getCredentials());
        $this->assertEquals($scopes, $service->getScopes());
        $this->assertEquals($token, $service->getToken());
    }

    /**
     * Test the setters and getters for the client.
     */
    public function test_set_and_get_client()
    {
        // Arrange
        $client1 = new Guzzle\Http\Client;
        $client2 = new Guzzle\Http\Client;

        // Act
        $service = new OAuth\Service($client1);
        
        // Assert
        $this->assertEquals($client1, $service->getClient());

        // Act
        $service->setClient($client2);

        // Assert
        $this->assertEquals($client2, $service->getClient());
    }

    /**
     * Test the setters and getters for the token.
     */
    public function test_set_and_get_token()
    {
        // Arrange
        $client = new Guzzle\Http\Client;
        $token = array('access_token' => 'your-access-token');

        // Act
        $service = new OAuth\Service($client);
        $service->setToken($token);

        // Assert
        $this->assertEquals($token, $service->getToken());
    }

    /**
     * Test the setters and getters for the credentials.
     */
    public function test_set_and_get_credentials()
    {
        // Arrange
        $client = new Guzzle\Http\Client;
        $credentials = array(
            'client_id'     => 'your-consumer-key-1',
            'client_secret' => 'your-consumer-secret-1',
        );

        // Act
        $service = new OAuth\Service($client);
        $service->setCredentials($credentials);

        // Assert
        $this->assertEquals($credentials, $service->getCredentials());
    }

    /**
     * Test the setters and getters for the scopes.
     */
    public function test_set_and_get_scopes()
    {
        // Arrange
        $client = new Guzzle\Http\Client;
        $scopes = array('email', 'profile',);

        // Act
        $service = new OAuth\Service($client);
        $service->setScopes($scopes);

        // Assert
        $this->assertEquals($scopes, $service->getScopes());
    }

    /**
     * Test the setters and getters for the redirect uri.
     */
    public function test_set_and_get_redirect_uri()
    {
        // Arrange
        $client = new Guzzle\Http\Client;
        $redirectUri = array('https://example.com/oauth/callback');

        // Act
        $service = new OAuth\Service($client);
        $service->setRedirectUri($redirectUri);

        // Assert
        $this->assertEquals($redirectUri, $service->getRedirectUri());
    }

}