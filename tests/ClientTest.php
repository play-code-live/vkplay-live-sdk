<?php

declare(strict_types=1);

namespace PlayCode\Tests\VKPlayLiveSDK;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PlayCode\VKPlayLiveSDK\Client;
use PlayCode\VKPlayLiveSDK\Exception\ClientException;
use PlayCode\VKPlayLiveSDK\Exception\InternalServerErrorException;
use PlayCode\VKPlayLiveSDK\Exception\InvalidParamException;
use PlayCode\VKPlayLiveSDK\Exception\ParseJsonException;
use PlayCode\VKPlayLiveSDK\Exception\RateLimitExceededException;
use PlayCode\VKPlayLiveSDK\Exception\UnauthorizedException;
use PlayCode\VKPlayLiveSDK\Scope;

class ClientTest extends TestCase
{
    #[DataProvider('getAuthLinkProvider')]
    public function testGetAuthLink(
        string $redirectUri,
        array $scope,
        string $expected,
        string $state = ''
    ): void {
        $authLink = (new ClientExtended([]))->getAuthLink($redirectUri, $scope, $state);

        $this->assertEquals($expected, $authLink);
    }

    public static function getAuthLinkProvider(): array
    {
        return [
            'empty scope' => [
                'https://domain.com/redirect',
                [],
                'https://vkplay.live/app/oauth2/authorize?client_id=clientId&redirect_uri=https%3A%2F%2Fdomain.com%2Fredirect&response_type=code&scope=&state=',
            ],
            'with scope' => [
                'https://test.ru/',
                [
                    Scope::CHANNEL_POINTS,
                    Scope::CHAT_SETTINGS,
                ],
                'https://vkplay.live/app/oauth2/authorize?client_id=clientId&redirect_uri=https%3A%2F%2Ftest.ru%2F&response_type=code&scope=channel%3Apoints%2Cchat%3Asettings&state=',
            ],
            'with state' => [
                'https://vkplay.live/redirect',
                [],
                'https://vkplay.live/app/oauth2/authorize?client_id=clientId&redirect_uri=https%3A%2F%2Fvkplay.live%2Fredirect&response_type=code&scope=&state=some_state',
                'some_state',
            ]
        ];
    }

    #[DataProvider('getAccessTokenProvider')]
    public function testGetAccessToken(Client $c, array $propValues, ?string $exception = null): void
    {
        $this->clientMethodCallTest(new MethodCallStruct(
            'getAccessToken',
            ['client_id', 'client_secret'],
            $c,
            $propValues,
            $exception
        ));
    }

    #[DataProvider('getAccessTokenProvider')]
    public function testRefreshToken(Client $c, array $propValues, ?string $exception = null): void
    {
        $this->clientMethodCallTest(new MethodCallStruct(
            'refreshToken',
            ['some_refresh_token'],
            $c,
            $propValues,
            $exception
        ));
    }

    public static function getAccessTokenProvider(): array
    {
        return [
            'success' => [
                new ClientExtended([
                    new Response(200, [], '{"access_token":"some_token","refresh_token":"refresh_token","expires_in":123,"token_type":"Bearer"}'),
                ]),
                [
                    'accessToken' => 'some_token',
                    'refreshToken' => 'refresh_token',
                    'expiresIn' => 123,
                    'tokenType' => 'Bearer',
                ]
            ],
            'internal server error' => [
                new ClientExtended([new Response(500, [], '')]),
                [],
                InternalServerErrorException::class,
            ],
            'bad json' => [
                new ClientExtended([new Response(200, [], 'error')]),
                [],
                ParseJsonException::class,
            ]
        ];
    }

    #[DataProvider('revokeTokenProvider')]
    public function testRevokeToken(Client $c, array $propValues, ?string $exception = null): void
    {
        $this->clientMethodCallTest(new MethodCallStruct(
            'revokeToken',
            ['some_token'],
            $c,
            $propValues,
            $exception
        ));
    }

    public static function revokeTokenProvider(): array
    {
        return [
            'success' => [
                new ClientExtended([
                    new Response(200, [], '{}'),
                ]),
                []
            ],
            'bad request' => [
                new ClientExtended([
                    new Response(400, [], '{}'),
                ]),
                [],
                InvalidParamException::class,
            ],
        ];
    }

    #[DataProvider('listChannelsProvider')]
    public function testListChannelsOnline(Client $c, array $propValues, ?string $exception = null)
    {
        $this->clientMethodCallTestRecursive(new MethodCallStruct(
            'listChannelsOnline',
            [20],
            $c,
            $propValues,
            $exception
        ));
    }

    public static function listChannelsProvider(): array
    {
        return [
            'success' => [
                new ClientExtended([
                    new Response(200, [], '{"data":{"channels":[{"channel":{"url":"play_code","cover_url":"https://test.url/image.png","status":"online","web_socket_channels":{"chat":"chat_socket","private_chat":"private_chat_socket","info":"info_socket","private_info":"private_info_socket","channel_points":"channel_points_socket","private_channel_points":"private_channel_points_socket","limited_chat":"limited_chat_socket","private_limited_chat":"private_limited_chat_socket"},"counters":{"subscribers":128}},"owner":{"id":1234,"nick":"play_code","nick_color":8,"avatar_url":"https://test.url/avatar.png","is_verified_streamer":true},"stream":{"id":"123e4567-e89b-12d3-a456-426655440000","title":"Тестовая трансляция","started_at":1694596420324,"ended_at":1694598320456,"preview_url":"https://test.url/preview.png","category":{"id":"123e4567-e89b-12d3-a456-665544420000","title":"World of Warcraft","type":"game"},"reactions":[{"count":123,"type":"heart"}],"counters":{"viewers":512,"views":1024}}}]}}'),
                ]),
                [
                    [
                        'channelInfo' => [
                            'url' => 'play_code',
                            'coverUrl' => 'https://test.url/image.png',
                            'status' => 'online',
                            'subscribers' => 128,
                            'webSocketChannels' => [
                                'chat' => 'chat_socket',
                                'privateChat' => 'private_chat_socket',
                                'info' => 'info_socket',
                                'privateInfo' => 'private_info_socket',
                                'channelPoints' => 'channel_points_socket',
                                'privateChannelPoints' => 'private_channel_points_socket',
                                'limitedChat' => 'limited_chat_socket',
                                'privateLimitedChat' => 'private_limited_chat_socket',
                            ]
                        ],
                        'owner' => [
                            'id' => 1234,
                            'nick' => 'play_code',
                            'nickColor' => '8',
                            'avatarUrl' => 'https://test.url/avatar.png',
                            'isVerifiedStreamer' => true,
                        ],
                        'streamInfo' => [
                            'id' => '123e4567-e89b-12d3-a456-426655440000',
                            'title' => 'Тестовая трансляция',
                            'startedAt' => 1694596420324,
                            'endedAt' => 1694598320456,
                            'previewUrl' => 'https://test.url/preview.png',
                            'reactions' => [
                                ['count'=>123,'type'=>'heart'],
                            ],
                            'counters' => ['viewers'=>512,'views'=>1024],
                            'category' => [
                                'id' => '123e4567-e89b-12d3-a456-665544420000',
                                'title' => 'World of Warcraft',
                                'type' => 'game',
                            ],
                        ]
                    ]
                ],
            ],
            'internal error' => [
                new ClientExtended([
                    new Response(400, [], '{}'),
                ]),
                [],
                ClientException::class,
            ],
            'bad json' => [
                new ClientExtended([
                    new Response(200, [], 'ok'),
                ]),
                [],
                ParseJsonException::class
            ],
            'token expired' => [
                new ClientExtended([
                    new Response(401, [], '{"error":"token_expired"}'),
                ]),
                [],
                UnauthorizedException::class
            ],
            'rate limit' => [
                new ClientExtended([
                    new Response(429, [], '{"error":"rate_limit"}'),
                ]),
                [],
                RateLimitExceededException::class
            ],
        ];
    }

    private function clientMethodCallTestRecursive(MethodCallStruct $params): void
    {
        if ($params->expectedExceptionClass !== null) {
            $this->expectException($params->expectedExceptionClass);
        }

        $result = $params->client->{$params->method}(...$params->args);
        $this->assertCount(count($params->expectedProperties), $result);

        if ($params->expectedExceptionClass == null && empty($params->expectedProperties)) {
            $this->assertEmpty($result);
            return;
        }

        $this->checkObjPropertiesRecursive($result, $params->expectedProperties);
    }

    private function checkObjPropertiesRecursive(object|array $obj, array $expected): void
    {
        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                $this->checkObjPropertiesRecursive(is_array($obj) ? $obj[$key] : $obj->{$key}, $value);
                continue;
            }

            $this->assertObjectHasProperty($key, $obj);
            $this->assertEquals($value, $obj->{$key}, sprintf('Failed asserting values by key "%s"', $key));
        }
    }

    private function clientMethodCallTest(MethodCallStruct $params): void
    {
        if ($params->expectedExceptionClass !== null) {
            $this->expectException($params->expectedExceptionClass);
        }

        $result = $params->client->{$params->method}(...$params->args);
        foreach ($params->expectedProperties as $property => $value) {
            $this->assertObjectHasProperty($property, $result);
            $this->assertEquals($value, $result->{$property});
        }

        if ($params->expectedExceptionClass == null && empty($params->expectedProperties)) {
            $this->assertNull($result);
        }
    }
}
