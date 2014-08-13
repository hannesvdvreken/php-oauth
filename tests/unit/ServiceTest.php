<?php

use OAuth\Service;
use GuzzleHttp\Client;

class ServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests the client setter and getter.
     *
     * @test
     */
    public function client_setter_and_getter()
    {
        // Arrange
        $service = new Service();
        $client = new Client();

        // Act
        $returned = $service->setClient($client)->getClient();

        // Assert
        $this->assertEquals($client, $returned);
    }

    /**
     * Test the service getter and setter for credentials.
     *
     * @test
     */
    public function credentials_setter_and_getter()
    {
        // Arrange
        $service = new Service();
        $credentials = [
            'client_id' => 'client-id',
            'client_secret' => 'client-secret'
        ];

        // Act
        $returned = $service->setCredentials($credentials)->getCredentials();

        // Assert
        $this->assertEquals($credentials, $returned);
    }

    /**
     * Test the service returns the same credentials as provided in the constructor.
     *
     * @test
     */
    public function credentials_via_constructor()
    {
        // Arrange
        $credentials = [
            'client_id' => 'client-id',
            'client_secret' => 'client-secret'
        ];

        // Act
        $returned = (new Service(null, $credentials))->getCredentials();

        // Assert
        $this->assertEquals($credentials, $returned);
    }
}
