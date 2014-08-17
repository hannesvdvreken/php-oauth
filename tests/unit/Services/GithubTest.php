<?php

use OAuth\Services\Github;

class GithubTest extends PHPUnit_Framework_Testcase
{
    /**
     * Test the parseAccessToken method.
     *
     * @test
     */
    public function parse_access_token()
    {
        // Arrange
        $token = ['access_token' => '4cc3st0k3n', 'refresh_token' => 'r3fr3sht0k3n'];
        $string = http_build_query($token);

        $service = new Github;

        // Act
        $returned = $service->parseAccessToken($string);

        // Assert
        $this->assertEquals($token, $returned);
    }
}
