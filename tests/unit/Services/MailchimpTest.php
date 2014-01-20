<?php

use OAuth\Services\Mailchimp;

class MailchimpTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getDatacenter method
     */
    public function test_get_datacenter()
    {
        // Arrange
        $token = array('access_token' => 'access-token');
        $dc = 'us1';

        // Act
        $client = $this->mockGuzzle();
        $client->shouldReceive('get')->once()
            ->with('https://login.mailchimp.com/oauth2/metadata?oauth_token='. $token['access_token'])
            ->andReturn(Mockery::self());
        $response = $this->mockGuzzleResponse(compact('dc'));
        $client->shouldReceive('send')->once()
            ->with(null)->andReturn($response);

        $mc = new Mailchimp($client);
        $result = $mc->getDatacenter($token);

        // Assert
        $this->assertEquals($dc, $result);
    }

    /**
     * Test the parse access token method
     */
    public function test_parse_access_token()
    {
        // Arrange
        $accessToken = 'access-token';
        $dc = 'us1';

        // Act
        $client = $this->mockGuzzle();
        $client->shouldReceive('get')->once()
            ->with('https://login.mailchimp.com/oauth2/metadata?oauth_token='. $accessToken)
            ->andReturn(Mockery::self());
        $response = $this->mockGuzzleResponse(compact('dc'));
        $client->shouldReceive('send')->once()
            ->with(null)->andReturn($response);

        $mc = new Mailchimp($client);
        $token = $mc->parseAccessToken(json_encode(array('access_token' => $accessToken)));
        $result = $mc->getDc();

        // Assert
        $this->assertEquals($dc, $result);
        $this->assertEquals($token, array('access_token' => $accessToken));
    }

    /**
     * Test the DC accessors
     */
    public function test_set_and_get_dc()
    {
        // Arrange
        $dc = 'us1';

        // Act
        $mc = new Mailchimp($this->mockGuzzle());
        $result = $mc->setDc($dc)->getDc();

        // Assert
        $this->assertEquals($dc, $result);
    }

    /**
     * Test the prepare method
     */
    public function test_prepare()
    {
        // Arrange
        $dc = 'us1';
        $accessToken = 'access-token';

        // Act
        $client = $this->mockGuzzle();
        $client->shouldReceive('setBaseUrl')->once()
            ->with('https://'. $dc .'.api.mailchimp.com/2.0/')
            ->andReturn(Mockery::self());
        $client->shouldReceive('setDefaultOption')->once()
            ->with('query', array('apikey' => $accessToken))->andReturn(Mockery::self());
        $mc = new Mailchimp($client);
        $mc->setDc($dc)->setToken(array('access_token' => $accessToken));
        $result = $mc->prepare();

        // Assert
        $this->assertEquals($client, $result);
    }
    
    /**
     * Mock and return a Guzzle client.
     */
    protected function mockGuzzle()
    {
        $client = Mockery::mock('Guzzle\Http\Client');
        $client->shouldReceive('setBaseUrl')->once()->with('')->andReturn(Mockery::self());

        return $client;
    }

    /**
     * Mock and return a Guzzle response object.
     */
    protected function mockGuzzleResponse($body)
    {
        $response = Mockery::mock('Guzzle\Http\Message\Response');
        $response->shouldReceive('json')->once()
            ->with()->andReturn($body);

        return $response;
    }
}