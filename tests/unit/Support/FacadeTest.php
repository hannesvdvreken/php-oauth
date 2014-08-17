<?php

use OAuth\Support\Facade;

class FacadeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function get_facade_accessor()
    {
        // Arrange
        $accessor = 'oauth';

        // Act
        $returned = Facade::getFacadeAccessor();

        // Assert
        $this->assertEquals($accessor, $returned);
    }
}
