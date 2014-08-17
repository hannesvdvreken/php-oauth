<?php

use OAuth\Support\ServiceProvider;
use OAuth\Support\Manager;

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tear down method
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Nothing to test here.
     * @test
     */
    public function boot()
    {
        // Arrange
        $app = Mockery::mock('Illuminate\Foundation\Application');
        $app->shouldReceive('offsetGet')->times(4)
            ->with('files')->andReturn($app);
        $app->shouldReceive('isDirectory')->times(4)
            ->andReturn(false);
        $app->shouldReceive('offsetGet')->once()
            ->with('path')->andReturn('');
        $provider = new ServiceProvider($app);

        // Act
        $provider->boot();

        // Assert (nothing to assert here)
    }

    /**
     * Test the register method.
     *
     * @test
     */
    public function register()
    {
        // Arrange
        $app = Mockery::mock('Illuminate\Foundation\Application');
        $dir = __DIR__ .'/../config';
        $dir = str_replace('tests/unit', 'src', $dir);

        $app->shouldReceive('offsetGet')->once()
            ->with('config')->andReturn($app);
        $app->shouldReceive('package')->once()
            ->with('hannesvdvreken/php-oauth', $dir);
        $app->shouldReceive('bind')->once()
            ->with('oauth', Mockery::on(function (\Closure $closure) {
                $app = Mockery::mock('Illuminate\Foundation\Application');
                $manager = new Manager($app);
                $app->shouldReceive('make')->once()
                    ->with('OAuth\Support\Manager')->andReturn($manager);
                $closure($app);
                return true;
            }));
        $provider = new ServiceProvider($app);

        // Act
        $provider->register();

        // Assert
    }
}
