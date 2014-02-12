# OAuth service classes with Guzzle
[![Build Status](https://travis-ci.org/hannesvdvreken/php-oauth.png?branch=master)](https://travis-ci.org/hannesvdvreken/php-oauth)

## Usage

Let's dive right in. 

### Setup

One only needs a Guzzle Client to get started.

```php
$client = new Guzzle\Http\Client;
$service = new OAuth\Services\Github($client);
```

Other possible configuration can be passed on with the constructor, like so:

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

$service = new OAuth\Services\Github($client, $redirectUri, $credentials, $scopes, $token);
```

An alternative way is the following:

```php
$service = new OAuth\Services\Github($client);
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

Even the Guzzle Client can be interchanged after the object creation.

```php
$service->setClient($client);
$client = $service->getClient();
```

### Requesting an API

The internal Guzzle Client can be called by calling the same methods on the service class.

```php
$response = $service->get('users/self')->send()->json();
```

or

```php
$post = array('status' => 'Tweeted with @hannesvdvreken/php-oauth');
$status = $twitter->post('statuses/update', null, $post)->send()->json();
```

The internal Guzzle Client will be called with the right token in the header or GET parameter.
All you need to do is load the service class with the correct credentials or tokens from your persistance layer or session.

## Laravel 4
If you're using Laravel 4, feel free to register the contained service provider (`OAuth\Support\ServiceProvider`).
It registers the `OAuth` class alias for the facade to use the following syntax to get a fully configured service class:

```php
$twitter = OAuth::consumer('twitter');
```

To create an empty config file in `app/config/packages` just use the artisan command:

```bash
php artisan config:publish hannesvdvreken/php-oauth
```

## OAuth 1.0a

For the OAuth1.0a functionality we internally use the Guzzle [OAuth Plugin](docs.guzzlephp.org/en/latest/plugins/oauth-plugin.html). An example:

```php
$client = new Guzzle\Http\Client;
$twitter = new OAuth\Services\Twitter($client, $redirectUri, $credentials);

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
$profile = $twitter->get('account/verify_credentials.json')->send()->json();
```

## OAuth 2

The OAuth2 flow is easier.

```php
$fb = new OAuth\Services\Facebook($client);

$url = $fb->authorizationUrl();

header('Location: '. $url);
```

In the callback controller...

```php
$fb->accessToken($code);

$profile = $fb->get('me')->send()->json();
```

## Supported services
- CampaignMonitor
- Dropbox
- Facebook
- Foursquare
- Github
- Google
- Instagram
- Mailchimp
- Twitter (OAuth1.0a)

## Contributing
Feel free to make a pull request. A new service class can be as simple as 22 lines of code.
Please try to be as [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) 
compliant as possible. There's no shame if you misplaced a bracket or so!

## License
MIT