# VKPlayLive SDK

## Examples

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