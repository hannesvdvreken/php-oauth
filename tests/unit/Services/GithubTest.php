<?php

use OAuth\Services\Github;

class GithubTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the parse access token method
     */
    public function test_parse_access_token()
    {
        // Arrange
        $token = array('access_token' => 'access-token');
        $body = http_build_query($token);

        // Act
        $github = new Github($this->mockGuzzle());
        $result = $github->parseAccessToken($body);

        // Assert
        $this->assertEquals($token, $result);
    }

    /**
     * Mock and return a Guzzle client
     */
    protected function mockGuzzle()
    {
        $client = Mockery::mock('Guzzle\Http\Client');
        $client->shouldReceive('setBaseUrl')->once()
            ->with('https://api.github.com/')->andReturn(Mockery::self());

        return $client;
    }
}