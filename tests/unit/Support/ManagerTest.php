<?php

use OAuth\Support\Manager;
use OAuth\Services\Twitter;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tear down method to evaluate mockery expectations.
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Test the consumer factory method.
     *
     * @test
     */
    public function consumer()
    {
        // Arrange
        $app = Mockery::mock('Illuminate\Foundation\Application');
        $redirectUri = 'https://example.org/callback';
        $scopes = ['email', 'read'];
        $credentials = ['client_id' => 'cl13nt1d', 'client_secret' => 'cl13nts3cr3t'];
        $twitter = new Twitter;

        $app->shouldReceive('make')->once()
            ->with('OAuth\Services\Twitter')->andReturn($twitter);
        $app->shouldReceive('offsetGet')->once()
            ->with('config')->andReturn($app);
        $app->shouldReceive('get')->once()
            ->with('php-oauth::oauth.consumers.twitter')->andReturn($credentials);

        $manager = new Manager($app);

        // Act
        $consumer = $manager->consumer('twitter', $redirectUri, $scopes);

        // Assert
        $this->assertEquals($credentials, $consumer->getCredentials());
        $this->assertEquals($scopes, $consumer->getScopes());
        $this->assertEquals($redirectUri, $consumer->getRedirectUri());
    }

    /**
     * Consumer with default arguments.
     *
     * @test
     */
    public function consumer_default_arguments()
    {
        // Arrange
        $app = Mockery::mock('Illuminate\Foundation\Application');
        $redirectUri = 'https://example.org/callback';
        $scopes = ['email', 'read'];
        $credentials = ['client_id' => 'cl13nt1d', 'client_secret' => 'cl13nts3cr3t'];
        $twitter = new Twitter;

        $app->shouldReceive('offsetGet')->twice()
            ->with('config')->andReturn($app);
        $app->shouldReceive('offsetGet')->once()
            ->with('url')->andReturn($app);
        $app->shouldReceive('current')->once()
            ->with()->andReturn($redirectUri);
        $app->shouldReceive('make')->once()
            ->with('OAuth\Services\Twitter')->andReturn($twitter);
        $app->shouldReceive('get')->once()
            ->with('php-oauth::oauth.consumers.twitter')->andReturn($credentials);
        $app->shouldReceive('get')->once()
            ->with('php-oauth::oauth.consumers.twitter.scopes', [])->andReturn($scopes);

        $manager = new Manager($app);

        // Act
        $consumer = $manager->consumer('twitter');

        // Assert
        $this->assertEquals($credentials, $consumer->getCredentials());
        $this->assertEquals($scopes, $consumer->getScopes());
        $this->assertEquals($redirectUri, $consumer->getRedirectUri());
    }
}
