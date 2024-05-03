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

#### Channels Online

```php
// Limit is required and cannot be more than 200
$client->listChannelsOnline(limit: 20);

// You can specify category id
$client->listChannelsOnline(20, categoryId: '4588a9f0-b606-4827-9b6a-f2da4309c196');

// Or category type
$client->listChannelsOnline(20, categoryType: Category::TYPE_GAME);

// It works with clientId and clientSecret, but you can use access_token
$client->listChannelsOnline(20, accessToken: $tokenData->getAccessToken());
```

#### Categories Online

```php
// Limit is required and cannot be more than 200
$client->listCategoriesOnline(limit: 20);

// You can specify category type
$client->listCategoriesOnline(20, categoryType: Category::TYPE_GAME);

// It works with clientId and clientSecret, but you can use access_token
$client->listCategoriesOnline(20, accessToken: $tokenData->getAccessToken());
```

### Channel

```php
// You can get channel data with clientId and clientSecret
$channelInfo = $client->getChannel('play_code');

// Or with access_token
$channelInfo = $client->getChannel('play_code', $tokenData->getAccessToken());

// It contains all the data about channel. Example:
$channelInfo->getChannelInfo()->getSubscribers(); // Followers count
$channelInfo->getChannelInfo()->getStatus(); // Current status

// Socket addresses
$channelInfo->getChannelInfo()->getWebSocketChannels()->getChat();

// Owner info
$channelInfo->getOwner()->getAvatarUrl();
$channelInfo->getOwner()->getNick();
$channelInfo->getOwner()->isVerifiedStreamer();

// Stream info
$channelInfo->getStreamInfo()->getTitle();
$channelInfo->getStreamInfo()->getCategory()->getTitle();
$channelInfo->getStreamInfo()->getCounters()->getViewers();
```

### Channels

```php
// In the same way you can fetch up to 100 channels
$channels = $client->getChannels([
    'play_code',
    'vkplay',
    'murmoshow',
]);

// Or with access_token
$channels = $client->getChannels([
    'play_code',
    'vkplay',
    'murmoshow',
], $tokenData->getAccessToken());
```

### Channel Credentials

```php
$credentials = $client->getChannelCredentials('play_code', $tokenData->getAccessToken());

// RTMP-stream url
$credentials->getUrl();

// Stream token
$credentials->getToken();
```