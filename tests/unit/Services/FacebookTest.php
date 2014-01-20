<?php

use OAuth\Services\Facebook;

class FacebookTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Teardown the test
	 */
	public function tearDown()
	{
		Mockery::close();
	}

	/**
	 * Test the parse access token method
	 */
	public function test_parse_access_token()
	{
		// Arrange
		$data = array(
			'access_token' => 'access-token',
			'expires' => 3600,
		);
		$body = http_build_query($data);
		$data['expires'] = new DateTime('now + '. $data['expires'] .' seconds');

		// Act
		$service = new Facebook($this->mockGuzzle());
		$result = $service->parseAccessToken($body);

		// Assert
		$this->assertEquals($data, $result);
	}

	/**
	 * Mock Guzzle client.
	 * 
	 * @return [type] [description]
	 */
	protected function mockGuzzle()
	{
		$client = Mockery::mock('Guzzle\Http\Client');
		$client->shouldReceive('setBaseUrl')->once()->with('https://graph.facebook.com')->andReturn(Mockery::self());

		return $client;
	}
}