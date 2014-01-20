<?php

require_once(__DIR__ .'/OAuthServiceTest.php');
use OAuth\OAuth2Service;

class OAuth2ServiceTest extends OAuthServiceTest
{
    /**
     * Test the authorization url
     */
    public function test_authorization_url()
    {
        // Arrange
        $options = array('option' => 'value');
        $scopes = array('scope-1', 'scope-2');
        $redirectUri = 'https://example.com/oauth/callback';
        $credentials = array(
            'client_id'     => 'client-id',
            //'client_secret' => 'client-secret',
        );
        $extra = array(
            'response_type' => 'code',
            'type'          => 'web_server',
        );

        $expected = '?'. http_build_query(array_merge(
            $options,
            $credentials,
            array('redirect_uri' => $redirectUri), 
            $extra,
            array('scope' => implode(' ', $scopes))
        ));

        // Act
        $client = $this->mockGuzzle();
        $service = new OAuth2Service($client, $redirectUri, $credentials, $scopes);
        $url = $service->authorizationUrl($options);

        // Assert
        $this->assertEquals($expected, $url);
    }

    /**
     * Test the access token request
     */
    public function test_access_token()
    {
        // Arrange
        $code = 'code';
        $credentials = array(
            'client_id'     => 'client-id',
            'client_secret' => 'client-secret',
        );
        $accessTokenEndpoint = '';
        $redirectUri = 'https://example.com/oauth/callback';
        $expected = array(
            'access_token' => 'access-token',
        );
        $post = array(
            'code'          => $code,
            'client_id'     => $credentials['client_id'],
            'client_secret' => $credentials['client_secret'],
            'redirect_uri'  => $redirectUri,
            'grant_type'    => 'authorization_code',
        );
        $body = json_encode($expected);

        // Act
        $client = $this->mockGuzzle();
        $client->shouldReceive('post')->once()->with($accessTokenEndpoint, null, $post)->andReturn(Mockery::self());
        $client->shouldReceive('send')->once()->with(null)->andReturn($this->mockGuzzleResponse($body));
        $service = new OAuth2Service($client, $redirectUri, $credentials);
        $accessToken = $service->accessToken($code);

        // Assert
        $this->assertEquals($expected, $accessToken);
    }
}