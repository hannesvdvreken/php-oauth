<?php

use OAuth\Services\Mailchimp;

class MailchimpTest extends PHPUnit_Framework_Testcase
{
    /**
     * Test the setDc method.
     *
     * @test
     */
    public function set_dc()
    {
        // Arrange
        $dc = 'us5';
        $service = new Mailchimp;

        // Act
        $service->setDc($dc);

        // Assert
        $this->assertEquals('https://us5.api.mailchimp.com/2.0/', $service->getClient()->getBaseUrl());
        $this->assertEquals($dc, $service->getDc());
    }

    /**
     * Test getDatacenter method with given access token.
     *
     * @test
     */
    public function get_datacenter()
    {
        // Arrange
        $body = ['dc' => $dc = 'us5', 'foo' => 'bar'];
        $token = ['access_token' => '4cc3st0k3n'];
        $client = Mockery::mock('GuzzleHttp\Client');
        $response = Mockery::mock('GuzzleHttp\Message\Response');
        $client->shouldReceive('get')->once()
            ->with('https://login.mailchimp.com/oauth2/metadata?oauth_token=4cc3st0k3n')
            ->andReturn($response);
        $response->shouldReceive('json')->once()
            ->andReturn($body);

        $service = new Mailchimp;
        $service->setClient($client);

        // Act
        $returned = $service->getDatacenter($token);

        // Assert
        $this->assertEquals($dc, $returned);
    }

    /**
     * getDatacenter method with default argument.
     *
     * @test
     */
    public function get_datacenter_default_argument()
    {
        // Arrange
        $body = ['dc' => $dc = 'us5', 'foo' => 'bar'];
        $token = ['access_token' => '4cc3st0k3n'];
        $client = Mockery::mock('GuzzleHttp\Client');
        $response = Mockery::mock('GuzzleHttp\Message\Response');
        $client->shouldReceive('get')->once()
            ->with('https://login.mailchimp.com/oauth2/metadata?oauth_token=4cc3st0k3n')
            ->andReturn($response);
        $response->shouldReceive('json')->once()
            ->andReturn($body);

        $service = new Mailchimp;
        $service->setClient($client);
        $service->setToken($token);

        // Act
        $returned = $service->getDatacenter();

        // Assert
        $this->assertEquals($dc, $returned);
    }

    /**
     * Test the prepare method.
     *
     * @test
     */
    public function prepare_and_get_dc()
    {
        // Arrange
        $body = ['dc' => $dc = 'us5'];
        $token = ['access_token' => '4cc3st0k3n'];
        $client = Mockery::mock('GuzzleHttp\Client');
        $response = Mockery::mock('GuzzleHttp\Message\Response');
        $client->shouldReceive('get')->once()
            ->with('https://login.mailchimp.com/oauth2/metadata?oauth_token=4cc3st0k3n')
            ->andReturn($response);
        $response->shouldReceive('json')->once()
            ->andReturn($body);

        $service = new Mailchimp;
        $service->setClient($client);
        $service->setToken($token);

        // Act
        $returned = $service->prepare();

        // Assert
        $this->assertNotEquals($client, $returned);
    }

    /**
     * Test the prepare method wih.
     *
     * @test
     */
    public function prepare_calls_parent()
    {
        // Arrange
        $body = ['dc' => $dc = 'us5'];
        $token = ['access_token' => '4cc3st0k3n'];
        $client = Mockery::mock('GuzzleHttp\Client');
        $response = Mockery::mock('GuzzleHttp\Message\Response');
        $client->shouldReceive('get')->once()
            ->with('https://login.mailchimp.com/oauth2/metadata?oauth_token=4cc3st0k3n')
            ->andReturn($response);
        $response->shouldReceive('json')->once()
            ->andReturn($body);

        $service = new Mailchimp;
        $service->setClient($client);
        $service->setToken($token);

        // Act
        $returned = $service->prepare();

        // Assert
        $this->assertNotEquals($client, $returned);
    }

    /**
     * Test the parse access token method
     *
     * @test
     */
    public function parse_access_token()
    {
        // Arrange
        $token = ['access_token' => '4cc3st0k3n'];
        $body = ['dc' => 'us5'];
        $string = json_encode($token);
        $expected = $token;
        $client = Mockery::mock('GuzzleHttp\Client');
        $response = Mockery::mock('GuzzleHttp\Message\Response');

        $client->shouldReceive('get')->once()
            ->with('https://login.mailchimp.com/oauth2/metadata?oauth_token=4cc3st0k3n')
            ->andReturn($response);
        $response->shouldReceive('json')->once()
            ->andReturn($body);

        $service = new Mailchimp;
        $service->setClient($client);

        // Act
        $returned = $service->parseAccessToken($string);

        // Assert
        $this->assertEquals($expected, $returned);
    }
}
