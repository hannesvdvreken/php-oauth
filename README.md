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
All you need to do is load it with the correct credentials or tokens from your persistance layer.

## Laravel4
If you're using Laravel 4, feel free to register the contained Service Provider (`OAuth\Support\ServiceProvider`).
It registers the `OAuth` class alias to use the following syntax to get a configured service class:

```php
$twitter = OAuth::consumer('twitter');
```

To publish a config file in `app/config/packages` just use the artisan command:

```bash
php artisan config:publish hannesvdvreken/php-oauth
```

## OAuth 1.0a
For the OAuth1.0a functionality we use the Guzzle [OAuth Plugin](docs.guzzlephp.org/en/latest/plugins/oauth-plugin.html).

## OAuth 2

## Supported services
- CampaignMonitor
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