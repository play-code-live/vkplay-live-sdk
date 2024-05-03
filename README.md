# VKPlayLive SDK

[![Latest Version](https://img.shields.io/github/release/play-code-live/vkplay-live-sdk.svg?style=flat-square)](https://github.com/play-code-live/vkplay-live-sdk/releases)
[![Maintainability](https://api.codeclimate.com/v1/badges/ae44a8e5065c0b28b0cb/maintainability)](https://codeclimate.com/github/play-code-live/vkplay-live-sdk/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ae44a8e5065c0b28b0cb/test_coverage)](https://codeclimate.com/github/play-code-live/vkplay-live-sdk/test_coverage)

## Installation

The recommended way to install VKPlayLiveSDK is through
[Composer](https://getcomposer.org/).

```bash
composer require play-code-live/vkplay-live-sdk
```

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
print("Access token: " . $tokenData->accessToken);
print("Refresh token: " . $tokenData->refreshToken);
print("Expires in: " . $tokenData->expiresIn);

// Refresh token
$tokenData = $client->refreshToken($tokenData->refreshToken);

// Revoke token
$client->revokeToken($tokenData->accessToken);
// or
$client->revokeToken($tokenData->refreshToken, RevokeRequest::HINT_REFRESH_TOKEN);

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
$client->listChannelsOnline(20, accessToken: $tokenData->accessToken);
```

#### Categories Online

```php
// Limit is required and cannot be more than 200
$client->listCategoriesOnline(limit: 20);

// You can specify category type
$client->listCategoriesOnline(20, categoryType: Category::TYPE_GAME);

// It works with clientId and clientSecret, but you can use access_token
$client->listCategoriesOnline(20, accessToken: $tokenData->accessToken);
```

### Category

```php
$category = $client->getCategory('3c6b4b27-75f2-49c4-b967-f15aa88e2038');
// or
$category = $client->getCategory('3c6b4b27-75f2-49c4-b967-f15aa88e2038', $tokenData->accessToken);

// Title of the category
$category->title;

// Image url
$category->coverUrl;

// Type of category. @see Category::class
$category->type;
```

### Category Search

```php
$category = $client->searchCategory('Говорим и смотрим', limit: 20);
// or
$category = $client->searchCategory('Говорим и смотрим', limit: 20, accessToken: $tokenData->accessToken);
```

### Channel

```php
// You can get channel data with clientId and clientSecret
$channelInfo = $client->getChannel('play_code');

// Or with access_token
$channelInfo = $client->getChannel('play_code', $tokenData->accessToken);

// It contains all the data about channel. Example:
$channelInfo->channelInfo->subscribers; // Followers count
$channelInfo->channelInfo->status; // Current status

// Socket addresses
$channelInfo->channelInfo->webSocketChannels->chat;

// Owner info
$channelInfo->owner->avatarUrl;
$channelInfo->owner->nick;
$channelInfo->owner->isVerifiedStreamer;

// Stream info
$channelInfo->streamInfo->title;
$channelInfo->streamInfo->category->title;
$channelInfo->streamInfo->counters->viewers;
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
], $tokenData->accessToken);
```

### Channel Credentials

```php
$credentials = $client->getChannelCredentials('play_code', $tokenData->accessToken);

// RTMP-stream url
$credentials->url;

// Stream token
$credentials->token;
```

## License

VKPlayLiveSDK is made available under the MIT License (MIT). Please see [License File](LICENSE) for more information.