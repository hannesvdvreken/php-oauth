# OAuth service classes with Guzzle (v4 and v5, but not v6)
[![Build Status](http://img.shields.io/travis/hannesvdvreken/php-oauth.svg?style=flat-square)](https://travis-ci.org/hannesvdvreken/php-oauth)
[![Latest Stable Version](http://img.shields.io/packagist/v/hannesvdvreken/php-oauth.svg?style=flat-square)](https://packagist.org/packages/hannesvdvreken/php-oauth)
[![Total Downloads](http://img.shields.io/packagist/dt/hannesvdvreken/php-oauth.svg?style=flat-square)](https://packagist.org/packages/hannesvdvreken/php-oauth)
[![Coverage Status](https://img.shields.io/coveralls/hannesvdvreken/php-oauth.svg?style=flat-square)](https://coveralls.io/r/hannesvdvreken/php-oauth?branch=master)
[![License](http://img.shields.io/packagist/l/hannesvdvreken/php-oauth.svg?style=flat-square)](#license)

## Usage

Let's dive right in.

### Setup

```php
$service = new OAuth\Services\Github();
```

Some possible configuration can be passed on with the constructor, like so:

```php
$redirectUri = 'https://example.com/oauth/callback';

$credentials = array(
    'client_id'     => 'client-id',
    'client_secret' => '****',
);

$scopes = array('user', 'user:email');

$token = array(
    'access_token' => $accessToken;
);

$service = new OAuth\Services\Github($redirectUri, $credentials, $scopes, $token);
```

An alternative way is the following:

```php
$service = new OAuth\Services\Github;
$service
    ->setRedirectUri($redirectUri)
    ->setCredentials($credentials)
    ->setScopes($scopes)
    ->setToken($token);
```

The service class also has the following getters:

```php
$redirectUri = $service->getRedirectUri();
$credentials = $service->getCredentials();
$scopes      = $service->getScopes();
$token       = $service->getToken();
```

The `GuzzleHttp\Client` underneath can be accessed like so:

```php
$service->setClient(new GuzzleHttp\Client);
$service->getClient();
```

### Requesting an API

The internal `GuzzleHttp\Client` can be called by calling the same methods on the service class.

```php
$response = $service->get('users/self')->json();
```

or

```php
$body = ['status' => 'Tweeted with @hannesvdvreken/php-oauth'];
$status = $twitter->post('statuses/update', compact('body'))->json();
```

The internal Guzzle Client will be called with the right token in the header or GET parameter.
All you need to do is load the service class with the correct credentials or tokens from your persistance layer or session.

## Laravel 4
If you're using Laravel 4, feel free to register the contained service provider (`OAuth\Support\ServiceProvider`).
Register the `OAuth` class alias for the facade to use the following syntax to get a fully configured service class:

```php
$twitter = OAuth::consumer('twitter');
```

To create an empty config file in `app/config/packages` just use the artisan command:

```bash
php artisan config:publish hannesvdvreken/php-oauth
```

## OAuth 1.0a

For the OAuth1.0a functionality we internally use the Guzzle [OAuth1 subscriber](https://github.com/guzzle/oauth-subscriber). An example:

```php
$twitter = new OAuth\Services\Twitter($redirectUri, $credentials);

// Request token for redirecting the user (store it in session afterwards).
$token = $twitter->requestToken();

// Get the url to which we need to redirect the user.
$url = $twitter->authorizationUrl();

// Redirect the user.
header('Location: '. $url); exit;
```

Or in short, the `authorizationUrl` will call the `requestToken` method, if you haven't done so already:

```php
// Get the url to which we need to redirect the user.
$url = $twitter->authorizationUrl();

// Get the requestToken that has been requested.
$token = $twitter->getToken();
// And store it.

// And redirect the user.
header('Location: '. $url); exit;
```

In the callback controller...

```php
// Give the stored token back to the service class.
$twitter->setToken($token);

// Exchange the get parameters for an access token.
$token = $twitter->accessToken($oauthToken, $oauthVerifier);

// Do a get request, just like you would do with a Guzzle Client.
$profile = $twitter->get('account/verify_credentials.json')->json();
```

## OAuth 2

The OAuth2 flow is easier.

```php
$fb = new OAuth\Services\Facebook();

$url = $fb->authorizationUrl();

header('Location: '. $url);
```

In the callback controller...

```php
$fb->accessToken($code);

$profile = $fb->get('me')->json();
```

## Supported services
- Campaign Monitor
- Dropbox
- Facebook
- Foursquare
- GitHub
- Google
- Instagram
- MailChimp
- Twitter (OAuth1.0a)
- Stack Exchange

## Guzzle v3

If you want to continue to work with the old versions of this library that
leveraged Guzzle v3 (`Guzzle\Http\Client` instead of `GuzzleHttp\Client`)
then you might want to install the `0.1.*` releases. Pull request with Guzzle v3 compatibility should be made against the `guzzle3` [branch](https://github.com/hannesvdvreken/php-oauth/tree/guzzle3). Install the latest guzzle v3 version with `0.1.*` or `dev-guzzle3`.

## Contributing
Feel free to make a pull request. A new service class can be as simple as 22 lines of code.
Please try to be as [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
compliant as possible. There's no shame if you misplaced a bracket or so!

### Testing

After installing the dependencies (`composer install`) you just need to run
`phpunit` to run the entire test-suite.

## License
[MIT](license)
