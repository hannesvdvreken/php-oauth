<?php

use OAuth\Services\Linkedin;

class LinkedinTest extends PHPUnit_Framework_Testcase
{
    /**
     * Test the parseAccessToken method.
     *
     * @test
     */
    public function parse_access_token()
    {
        // Arrange
        $token = ['access_token' => '4cc3st0k3n', 'expires_in' => 60];
        $expires = new DateTime('now + 60 seconds');
        $string = json_encode($token);
        $expected = [
            'access_token' => '4cc3st0k3n',
            'expires' => $expires,
        ];

        $service = new Linkedin;

        // Act
        $returned = $service->parseAccessToken($string);

        // Assert
        $this->assertEquals($expected, $returned);
    }
}
