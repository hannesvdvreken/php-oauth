<?php

use OAuth\Services\CampaignMonitor;

class CampaignMonitorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the parse access token method
     */
    public function test_parse_access_token()
    {
        // Arrange
        $token = array(
            'access_token' => $accessToken = 'access-token',
            'refresh_token' => $refreshToken = 'refresh-token',
            'expires_in'   => 3600,
        );
        $expected = array(
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires' => new DateTime('now + '. $token['expires_in'] .' seconds'),
        );

        // Act
        $cm = new CampaignMonitor($this->mockGuzzle());
        $result = $cm->parseAccessToken(json_encode($token));

        // Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * Mock and return a Guzzle Client.
     */
    protected function mockGuzzle()
    {
        $client = Mockery::mock('Guzzle\Http\Client');
        $client->shouldReceive('setBaseUrl')->once()
            ->with('https://api.createsend.com/api/v3.1/')->andReturn(Mockery::self());

        return $client;
    }
}