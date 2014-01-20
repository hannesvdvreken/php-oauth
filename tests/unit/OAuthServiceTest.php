<?php

abstract class OAuthServiceTest extends PHPUnit_Framework_TestCase
{
	/**
     * Mocks a Guzzle Client and returns it.
     * 
     * @return Guzzle\Http\Client
     */
    protected function mockGuzzle()
    {
        // Create mock.
        $client = Mockery::mock('Guzzle\Http\Client');

        // This is called in service constructor.
        $client->shouldReceive('setBaseUrl')->once()->with('')->andReturn(Mockery::self());

        // Return the client.
        return $client;
    }

    /**
     * Mock response object
     */
    protected function mockGuzzleResponse($body)
    {
        // Mock guzzle response.
        $response = Mockery::mock('Guzzle\Http\Message\Response');

        // Should receive getBody(true).
        $response->shouldReceive('getBody')->once()->with(true)->andReturn($body);

        // Return the mock object.
        return $response;
    }
}