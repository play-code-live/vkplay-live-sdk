# VKPlayLive SDK

## Authorization

```php

use PlayCode\VKPlayLiveSDK\Client;
use PlayCode\VKPlayLiveSDK\Scope;
use PlayCode\VKPlayLiveSDK\Request\RevokeRequest;

[
    $clientId,
    $clientSecret,
    $redirectUri
] = ['...', '...', '...'];

// Initialize the client
$client = new Client($clientId, $clientSecret);

// Get auth link
$authLink = $client->getAuthLink($redirectUri, [
    Scope::CHANNEL_POINTS,
    Scope::CHANNEL_ROLES,
]);

// We'll get Code form the URL query parameter `?code`
$code = '...';

// Get token data
$tokenData = $client->getAccessToken($code, $redirectUri);
print("Access token: " . $tokenData->getAccessToken());
print("Refresh token: " . $tokenData->getRefreshToken());
print("Expires in: " . $tokenData->getExpiresIn());

// Refresh token
$tokenData = $client->refreshToken($tokenData->getRefreshToken());

// Revoke token
$client->revokeToken($tokenData->getAccessToken());
// or
$client->revokeToken($tokenData->getRefreshToken(), RevokeRequest::HINT_REFRESH_TOKEN);

```

## Methods

### Catalog

```php
// Limit is required
$client->listChannelsOnline(limit: 20);

// You can specify category id
$client->listChannelsOnline(20, categoryId: '4588a9f0-b606-4827-9b6a-f2da4309c196');

// Or category type
$client->listChannelsOnline(20, categoryType: 'game');

// It works with clientId and clientSecret, but you can use access_token
$client->listChannelsOnline(20, accessToken: $tokenData->getAccessToken());
```