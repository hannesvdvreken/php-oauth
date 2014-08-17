<?php

use OAuth\Services\Google;

class GoogleTest extends PHPUnit_Framework_Testcase
{
    /**
     * Test the parseAccessToken method.
     *
     * @test
     */
    public function parse_access_token()
    {
        // Arrange
        $token = ['access_token' => '4cc3st0k3n', 'refresh_token' => 'r3fr3sht0k3n', 'expires_in' => 60];
        $expires = new DateTime('now + 60 seconds');
        $string = json_encode($token);
        $expected = [
            'access_token' => '4cc3st0k3n',
            'refresh_token' => 'r3fr3sht0k3n',
            'expires' => $expires,
        ];

        $service = new Google;

        // Act
        $returned = $service->parseAccessToken($string);

        // Assert
        $this->assertEquals($expected, $returned);
    }
}
