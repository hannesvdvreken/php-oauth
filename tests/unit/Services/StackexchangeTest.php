<?php

use OAuth\Services\Stackexchange;

class StackexchangeTest extends PHPUnit_Framework_Testcase
{
    /**
     * Tear down method to evaluate mockery expectations.
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Test the parseAccessToken method.
     *
     * @test
     */
    public function parse_access_token()
    {
        // Arrange
        $token = ['access_token' => '4cc3st0k3n', 'expires' => 60];
        $expires = new DateTime('now + 60 seconds');
        $string = http_build_query($token);
        $expected = [
            'access_token' => '4cc3st0k3n',
            'expires' => $expires,
        ];

        $service = new Stackexchange;

        // Act
        $returned = $service->parseAccessToken($string);

        // Assert
        $this->assertEquals($expected, $returned);
    }

    /**
     * Check if setClient method is correctly overwritten.
     *
     * @test
     */
    public function set_client()
    {
        // Arrange
        $client = Mockery::mock('GuzzleHttp\Client');
        $client->shouldReceive('setDefaultOption')->once()
            ->with('headers/Accept-Encoding', 'gzip');

        // Act
        $returned = (new Stackexchange)->setClient($client)->getClient();

        // Assert
        $this->assertEquals($client, $returned);
    }
}
