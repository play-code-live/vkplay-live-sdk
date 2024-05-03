<?php

declare(strict_types=1);

namespace PlayCode\Tests\VKPlayLiveSDK;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PlayCode\VKPlayLiveSDK\Client;
use PlayCode\VKPlayLiveSDK\Exception\ClientException;
use PlayCode\VKPlayLiveSDK\Exception\ParseJsonException;
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
                ClientException::class,
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
                ClientException::class,
            ],
        ];
    }

    private function clientMethodCallTest(MethodCallStruct $params): void {
        if ($params->expectedExceptionClass !== null) {
            $this->expectException($params->expectedExceptionClass);
        }

        $tokenData = $params->client->{$params->method}(...$params->args);
        foreach ($params->expectedProperties as $property => $value) {
            $this->assertObjectHasProperty($property, $tokenData);
            $this->assertEquals($value, $tokenData->{$property});
        }

        if ($params->expectedExceptionClass == null && empty($params->expectedProperties)) {
            $this->assertEmpty($params->expectedProperties);
        }
    }
}
