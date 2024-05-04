<?php

declare(strict_types=1);

namespace PlayCode\Tests\VKPlayLiveSDK;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PlayCode\VKPlayLiveSDK\DTO\CategoryDTO;
use PlayCode\VKPlayLiveSDK\DTO\CategoryWithCoverDTO;
use PlayCode\VKPlayLiveSDK\DTO\ChannelDTO;
use PlayCode\VKPlayLiveSDK\DTO\ChannelInfoDTO;
use PlayCode\VKPlayLiveSDK\DTO\CountersDTO;
use PlayCode\VKPlayLiveSDK\DTO\OwnerDTO;
use PlayCode\VKPlayLiveSDK\DTO\ReactionDTO;
use PlayCode\VKPlayLiveSDK\DTO\SmallCategoryDTO;
use PlayCode\VKPlayLiveSDK\DTO\StreamInfoDTO;
use PlayCode\VKPlayLiveSDK\DTO\WebSocketChannelsDTO;
use PlayCode\VKPlayLiveSDK\Request\CategoryRequest;
use PlayCode\VKPlayLiveSDK\Request\ChannelsRequest;
use PlayCode\VKPlayLiveSDK\Request\OnlineCategoriesRequest;
use PlayCode\VKPlayLiveSDK\Request\OnlineChannelsRequest;
use PlayCode\VKPlayLiveSDK\Request\SearchCategoriesRequest;
use PlayCode\VKPlayLiveSDK\Response\CategoriesResponse;
use PlayCode\VKPlayLiveSDK\Response\CategoryResponse;
use PlayCode\VKPlayLiveSDK\Response\ChannelResponse;
use PlayCode\VKPlayLiveSDK\Response\ChannelsResponse;
use PlayCode\VKPlayLiveSDK\Response\OnlineChannelsResponse;
use PlayCode\VKPlayLiveSDK\Response\Response as SdkResponse;
use PlayCode\VKPlayLiveSDK\Category;
use PlayCode\VKPlayLiveSDK\Client;
use PlayCode\VKPlayLiveSDK\Exception\ClientException;
use PlayCode\VKPlayLiveSDK\Exception\ExceptionFactory;
use PlayCode\VKPlayLiveSDK\Exception\ForbiddenException;
use PlayCode\VKPlayLiveSDK\Exception\InternalServerErrorException;
use PlayCode\VKPlayLiveSDK\Exception\InvalidParamException;
use PlayCode\VKPlayLiveSDK\Exception\NotFoundException;
use PlayCode\VKPlayLiveSDK\Exception\ParseJsonException;
use PlayCode\VKPlayLiveSDK\Exception\RateLimitExceededException;
use PlayCode\VKPlayLiveSDK\Exception\ServiceUnavailableException;
use PlayCode\VKPlayLiveSDK\Exception\UnauthorizedException;
use PlayCode\VKPlayLiveSDK\Exception\UnprocessableEntityException;
use PlayCode\VKPlayLiveSDK\Request\AccessTokenRequest;
use PlayCode\VKPlayLiveSDK\Request\AppAndUserAuthRequest;
use PlayCode\VKPlayLiveSDK\Request\ChannelCredentialsRequest;
use PlayCode\VKPlayLiveSDK\Request\ChannelRequest;
use PlayCode\VKPlayLiveSDK\Request\RefreshTokenRequest;
use PlayCode\VKPlayLiveSDK\Request\RevokeRequest;
use PlayCode\VKPlayLiveSDK\Response\ChannelCredentialsResponse;
use PlayCode\VKPlayLiveSDK\Response\JsonResponse;
use PlayCode\VKPlayLiveSDK\Response\TokenResponse;
use PlayCode\VKPlayLiveSDK\Scope;

#[UsesClass(Client::class)]
#[CoversClass(Client::class)]
#[CoversClass(ExceptionFactory::class)]
#[CoversClass(ClientException::class)]
#[CoversClass(ForbiddenException::class)]
#[CoversClass(InternalServerErrorException::class)]
#[CoversClass(InvalidParamException::class)]
#[CoversClass(NotFoundException::class)]
#[CoversClass(ParseJsonException::class)]
#[CoversClass(RateLimitExceededException::class)]
#[CoversClass(ServiceUnavailableException::class)]
#[CoversClass(UnauthorizedException::class)]
#[CoversClass(UnprocessableEntityException::class)]
#[CoversClass(AppAndUserAuthRequest::class)]
#[CoversClass(AccessTokenRequest::class)]
#[CoversClass(RefreshTokenRequest::class)]
#[CoversClass(RevokeRequest::class)]
#[CoversClass(ChannelCredentialsRequest::class)]
#[CoversClass(ChannelRequest::class)]
#[CoversClass(ChannelsRequest::class)]
#[CoversClass(CategoryRequest::class)]
#[CoversClass(SearchCategoriesRequest::class)]
#[CoversClass(OnlineChannelsRequest::class)]
#[CoversClass(OnlineCategoriesRequest::class)]
#[CoversClass(SdkResponse::class)]
#[CoversClass(JsonResponse::class)]
#[CoversClass(TokenResponse::class)]
#[CoversClass(ChannelCredentialsResponse::class)]
#[CoversClass(ChannelResponse::class)]
#[CoversClass(ChannelsResponse::class)]
#[CoversClass(CategoryResponse::class)]
#[CoversClass(CategoriesResponse::class)]
#[CoversClass(OnlineChannelsResponse::class)]
#[CoversClass(ChannelDTO::class)]
#[CoversClass(ChannelInfoDTO::class)]
#[CoversClass(CountersDTO::class)]
#[CoversClass(ReactionDTO::class)]
#[CoversClass(OwnerDTO::class)]
#[CoversClass(CategoryDTO::class)]
#[CoversClass(SmallCategoryDTO::class)]
#[CoversClass(CategoryWithCoverDTO::class)]
#[CoversClass(StreamInfoDTO::class)]
#[CoversClass(WebSocketChannelsDTO::class)]
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
            ...self::getDefaultExceptionCases(),
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
            ...self::getDefaultExceptionCases(false),
        ];
    }

    #[DataProvider('listChannelsProvider')]
    public function testListChannelsOnline(Client $c, array $propValues, ?string $exception = null)
    {
        $this->clientMethodCallTestRecursive(new MethodCallStruct(
            'listChannelsOnline',
            [20, 'cat_id', Category::TYPE_IRL],
            $c,
            $propValues,
            $exception
        ));
    }

    #[DataProvider('listChannelsProvider')]
    public function testGetChannels(Client $c, array $propValues, ?string $exception = null): void
    {
        $this->clientMethodCallTestRecursive(new MethodCallStruct(
            'getChannels',
            [['play_code', 'some_access_token']],
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
                    new Response(200, [], '{"data":{"channels":[{"channel":{"url":"play_code","cover_url":"https://test.url/image.png","status":"online","web_socket_channels":{"chat":"chat_socket","private_chat":"private_chat_socket","info":"info_socket","private_info":"private_info_socket","channel_points":"channel_points_socket","private_channel_points":"private_channel_points_socket","limited_chat":"limited_chat_socket","limited_private_chat":"private_limited_chat_socket"},"counters":{"subscribers":128}},"owner":{"id":1234,"nick":"play_code","nick_color":8,"avatar_url":"https://test.url/avatar.png","is_verified_streamer":true},"stream":{"id":"123e4567-e89b-12d3-a456-426655440000","title":"Тестовая трансляция","started_at":1694596420324,"ended_at":1694598320456,"preview_url":"https://test.url/preview.png","category":{"id":"123e4567-e89b-12d3-a456-665544420000","title":"World of Warcraft","type":"game"},"reactions":[{"count":123,"type":"heart"}],"counters":{"viewers":512,"views":1024}}}]}}'),
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
            ...self::getDefaultExceptionCases(),
        ];
    }

    #[DataProvider('listCategoriesProvider')]
    public function testListCategoriesOnline(Client $c, array $propValues, ?string $exception = null): void
    {
        $this->clientMethodCallTestRecursive(new MethodCallStruct(
            'listCategoriesOnline',
            [1],
            $c,
            $propValues,
            $exception
        ));
    }

    #[DataProvider('listCategoriesProvider')]
    public function testSearchCategory(Client $c, array $propValues, ?string $exception = null): void
    {
        $this->clientMethodCallTestRecursive(new MethodCallStruct(
            'searchCategory',
            ['some search query', Category::TYPE_IRL, 1],
            $c,
            $propValues,
            $exception
        ));
    }

    public static function listCategoriesProvider(): array
    {
        return [
            'success' => [
                new ClientExtended([
                    new Response(200, [], '{"data":{"categories":[{"id":"some_id","title":"Говорим и смотрим","cover_url":"https://test.url/cover.png","type":"irl","counters":{"viewers":1514}}]}}'),
                ]),
                [
                    [
                        'id' => 'some_id',
                        'title' => 'Говорим и смотрим',
                        'type' => 'irl',
                        'coverUrl' => 'https://test.url/cover.png',
                        'viewers' => 1514,
                    ]
                ]
            ],
            ...self::getDefaultExceptionCases(),
        ];
    }

    #[DataProvider('getCategoryProvider')]
    public function testGetCategory(Client $c, array $propValues, ?string $exception = null): void
    {
        $this->clientMethodCallTest(new MethodCallStruct(
            'getCategory',
            ['category_id', 'some_access_token'],
            $c,
            $propValues,
            $exception
        ));
    }

    public static function getCategoryProvider(): array
    {
        return [
            'success' => [
                new ClientExtended([
                    new Response(200, [], '{"data":{"category":{"id":"cat_id","title":"Говорим и смотрим","cover_url":"https://test.url/cover.png","type":"irl"}}}')
                ]),
                [
                    'id' => 'cat_id',
                    'title' => 'Говорим и смотрим',
                    'coverUrl' => 'https://test.url/cover.png',
                    'type' => 'irl',
                ]
            ],
            ...self::getDefaultExceptionCases(),
        ];
    }

    #[DataProvider('getChannelProvider')]
    public function testGetChannel(Client $c, array $propValues, ?string $exception = null): void
    {
        $this->clientMethodCallTestRecursive(new MethodCallStruct(
            'getChannel',
            ['play_code', 'some_access_token'],
            $c,
            $propValues,
            $exception
        ));
    }

    public static function getChannelProvider(): array
    {
        return [
            'success' => [
                new ClientExtended([
                    new Response(200, [], '{"data":{"channel":{"url":"play_code","cover_url":"","web_socket_channels":{"chat":"chat_socket","private_chat":"private_chat_socket","info":"info_socket","private_info":"private_info_socket","channel_points":"cp_socket","private_channel_points":"private_cp_socket","limited_chat":"lim_chat_socket","limited_private_chat":"private_lim_chat_socket"},"status":"offline","counters":{"subscribers":140}},"owner":{"avatar_url":"https://test.url/avatar.png","nick":"play_code","nick_color":15,"id":9671656,"is_verified_streamer":false},"stream":{"id":"stream_id","title":"Это просто мок","started_at":2,"ended_at":6,"counters":{"viewers":1,"views":10},"reactions":[],"category":{"title":"Разработка игр и ПО","type":"irl","id":"5c76243f-94ba-42d4-bc4f-b80ce868697a"},"preview_url":"","source_urls":[],"video_id":"0"}}}'),
                ]),
                [
                    'channelInfo' => [
                        'url' => 'play_code',
                        'coverUrl' => '',
                        'webSocketChannels' => [
                            'chat' => 'chat_socket',
                            'privateChat' => 'private_chat_socket',
                            'info' => 'info_socket',
                            'privateInfo' => 'private_info_socket',
                            'channelPoints' => 'cp_socket',
                            'privateChannelPoints' => 'private_cp_socket',
                            'limitedChat' => 'lim_chat_socket',
                            'privateLimitedChat' => 'private_lim_chat_socket',
                        ],
                        'status' => 'offline',
                        'subscribers' => 140,
                    ],
                    'owner' => [
                        'avatarUrl' => 'https://test.url/avatar.png',
                        'nick' => 'play_code',
                        'nickColor' => '15',
                        'id' => 9671656,
                        'isVerifiedStreamer' => false,
                    ],
                    'streamInfo' => [
                        'id' => 'stream_id',
                        'title' => 'Это просто мок',
                        'startedAt' => 2,
                        'endedAt' => 6,
                        'counters' => ['viewers' => 1, 'views' => 10],
                        'reactions' => [],
                        'category' => [
                            'title' => 'Разработка игр и ПО',
                            'type' => 'irl',
                            'id' => '5c76243f-94ba-42d4-bc4f-b80ce868697a'
                        ],
                        'previewUrl' => '',
                        'videoId' => '0'
                    ]
                ],
            ],
            ...self::getDefaultExceptionCases(),
        ];
    }

    #[DataProvider('getChannelCredentialsProvider')]
    public function testGetChannelCredentials(Client $c, array $propValues, ?string $exception = null): void
    {
        $this->clientMethodCallTest(new MethodCallStruct(
            'getChannelCredentials',
            ['play_code', 'some_access_token'],
            $c,
            $propValues,
            $exception
        ));
    }

    public static function getChannelCredentialsProvider(): array
    {
        return [
            'success' => [
                new ClientExtended([
                    new Response(200, [], '{"data":{"url":"rtmp://stream.mock.url","token":"super_secret_token"}}'),
                ]),
                [
                    'url' => 'rtmp://stream.mock.url',
                    'token' => 'super_secret_token',
                ],
            ],
            ...self::getDefaultExceptionCases(),
        ];
    }

    private static function getDefaultExceptionCases(bool $withJsonError = true): array
    {
        return [
            'forbidden' => [
                new ClientExtended([
                    new Response(403, [], '{"error":"forbidden"}'),
                ]),
                [],
                ForbiddenException::class
            ],
            'internal error' => [
                new ClientExtended([
                    new Response(500, [], '{"error":"internal error"}'),
                ]),
                [],
                InternalServerErrorException::class,
            ],
            'bad request' => [
                new ClientExtended([
                    new Response(400, [], '{"error":"bad request"}'),
                ]),
                [],
                InvalidParamException::class,
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
            'not found' => [
                new ClientExtended([
                    new Response(404, [], '{"error":"not found"}'),
                ]),
                [],
                NotFoundException::class
            ],
            'service unavailable' => [
                new ClientExtended([
                    new Response(503, [], '{"error":"service unavailable"}'),
                ]),
                [],
                ServiceUnavailableException::class
            ],
            'unknown error' => [
                new ClientExtended([
                    new Response(418, [], '{"error":"I am teapot"}'),
                ]),
                [],
                ClientException::class
            ],
            'bad entity' => [
                new ClientExtended([
                    new Response(422, [], '{"error":"unprocessable entity"}'),
                ]),
                [],
                UnprocessableEntityException::class
            ]
        ] + (!$withJsonError ? [] : [
            'bad json' => [
                new ClientExtended([
                    new Response(200, [], 'ok'),
                ]),
                [],
                ParseJsonException::class
    ],
        ]);
    }

    private function clientMethodCallTestRecursive(MethodCallStruct $params): void
    {
        if ($params->expectedExceptionClass !== null) {
            $this->expectException($params->expectedExceptionClass);
        }

        $result = $params->client->{$params->method}(...$params->args);

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
                try {
                    $this->checkObjPropertiesRecursive(is_array($obj) ? $obj[$key] : $obj->{$key}, $value);
                } catch (ExpectationFailedException $e) {
                    throw $e;
                } catch (\Throwable $e) {
                    $this->fail(sprintf("Error checking key '%s': %s", $key, $e->getMessage()));
                }

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
