<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use PlayCode\VKPlayLiveSDK\DTO\CategoryDTO;
use PlayCode\VKPlayLiveSDK\DTO\CategoryWithCoverDTO;
use PlayCode\VKPlayLiveSDK\DTO\ChannelDTO;
use PlayCode\VKPlayLiveSDK\Exception\ClientException;
use PlayCode\VKPlayLiveSDK\Exception\ExceptionFactory;
use PlayCode\VKPlayLiveSDK\Request\CategoryRequest;
use PlayCode\VKPlayLiveSDK\Request\ChannelCredentialsRequest;
use PlayCode\VKPlayLiveSDK\Request\ChannelRequest;
use PlayCode\VKPlayLiveSDK\Request\ChannelsRequest;
use PlayCode\VKPlayLiveSDK\Request\OnlineCategoriesRequest;
use PlayCode\VKPlayLiveSDK\Request\OnlineChannelsRequest;
use PlayCode\VKPlayLiveSDK\Request\RefreshTokenRequest;
use PlayCode\VKPlayLiveSDK\Request\RequestInterface;
use PlayCode\VKPlayLiveSDK\Request\RevokeRequest;
use PlayCode\VKPlayLiveSDK\Request\AccessTokenRequest;
use PlayCode\VKPlayLiveSDK\Request\SearchCategoriesRequest;
use PlayCode\VKPlayLiveSDK\Response\CategoryResponse;
use PlayCode\VKPlayLiveSDK\Response\ChannelCredentialsResponse;
use PlayCode\VKPlayLiveSDK\Response\ChannelResponse;
use PlayCode\VKPlayLiveSDK\Response\ChannelsResponse;
use PlayCode\VKPlayLiveSDK\Response\CategoriesResponse;
use PlayCode\VKPlayLiveSDK\Response\OnlineChannelsResponse;
use PlayCode\VKPlayLiveSDK\Response\Response;
use PlayCode\VKPlayLiveSDK\Response\ResponseInterface;
use PlayCode\VKPlayLiveSDK\Response\TokenResponse;

class Client
{
    private const API_HOST = 'https://apidev.live.vkplay.ru/';
    private string $clientId;
    private string $clientSecret;
    protected HttpClient $client;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;

        $this->client = new HttpClient([
            'base_uri' => self::API_HOST,
            'verify' => false
        ]);
    }

    public function getAuthLink(string $redirectUri, array $scope = [], string $state = ""): string
    {
        return sprintf("https://vkplay.live/app/oauth2/authorize?%s", http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => implode(",", $scope),
            'state'         => $state,
        ]));
    }

    /**
     * @throws ClientException
     */
    public function getAccessToken(string $code, string $redirectUri): TokenResponse
    {
        $request = new AccessTokenRequest($code, $this->clientId, $this->clientSecret, $redirectUri);

        return TokenResponse::createFromResponse($this->sendRequest($request));
    }

    /**
     * @throws ClientException
     */
    public function refreshToken(string $refreshToken): TokenResponse
    {
        $request = new RefreshTokenRequest($refreshToken, $this->clientId, $this->clientSecret);

        return TokenResponse::createFromResponse($this->sendRequest($request));
    }

    /**
     * @throws ClientException
     */
    public function revokeToken(string $token, string $tokenTypeHint = RevokeRequest::HINT_ACCESS_TOKEN): void
    {
        $request = new RevokeRequest($this->clientId, $this->clientSecret, $token, $tokenTypeHint);
        $this->sendRequest($request);
    }

    /**
     * @return ChannelDTO[]
     * @throws ClientException
     */
    public function listChannelsOnline(int $limit, string $categoryId = '', string $categoryType = '', ?string $accessToken = null): array
    {
        $request = new OnlineChannelsRequest(
            $this->clientId,
            $this->clientSecret,
            $limit,
            $categoryId,
            $categoryType,
            $accessToken
        );

        return OnlineChannelsResponse::createFromResponse($this->sendRequest($request))->channels;
    }

    /**
     * @return CategoryDTO[]
     * @throws ClientException
     */
    public function listCategoriesOnline(int $limit, string $categoryType = '', ?string $accessToken = null): array
    {
        $request = new OnlineCategoriesRequest(
            $this->clientId,
            $this->clientSecret,
            $limit,
            $categoryType,
            $accessToken
        );

        return CategoriesResponse::createFromResponse($this->sendRequest($request))->categories;
    }

    /**
     * @throws ClientException
     */
    public function getCategory(string $categoryId, ?string $accessToken = null): CategoryWithCoverDTO
    {
        $request = new CategoryRequest(
            $categoryId,
            $this->clientId,
            $this->clientSecret,
            $accessToken
        );

        return CategoryResponse::createFromResponse($this->sendRequest($request))->category;
    }

    /**
     * @return CategoryDTO[]
     * @throws ClientException
     */
    public function searchCategory(
        string $query,
        string $categoryType,
        int $limit,
        ?string $accessToken = null
    ): array {
        $request = new SearchCategoriesRequest(
            $this->clientId,
            $this->clientSecret,
            $query,
            $categoryType,
            $limit,
            $accessToken
        );

        return CategoriesResponse::createFromResponse($this->sendRequest($request))->categories;
    }

    /**
     * @throws ClientException
     */
    public function getChannel(string $channelUrl, ?string $accessToken = null): ChannelDTO
    {
        $request = new ChannelRequest($channelUrl, $this->clientId, $this->clientSecret, $accessToken);

        return ChannelResponse::createFromResponse($this->sendRequest($request))->channel;
    }

    /**
     * @param string[] $channelUrls
     * @param string|null $accessToken
     * @return ChannelDTO[]
     * @throws ClientException
     */
    public function getChannels(array $channelUrls, ?string $accessToken = null): array
    {
        $request = new ChannelsRequest($channelUrls, $this->clientId, $this->clientSecret, $accessToken);

        return ChannelsResponse::createFromResponse($this->sendRequest($request))->channels;
    }

    /**
     * @throws ClientException
     */
    public function getChannelCredentials(string $channelUrl, string $accessToken): ChannelCredentialsResponse
    {
        $request = new ChannelCredentialsRequest($channelUrl, $accessToken);

        return ChannelCredentialsResponse::createFromResponse($this->sendRequest($request));
    }

    /**
     * @throws ClientException
     */
    private function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            $options = [
                'headers' => $request->getHeaders(),
            ];

            if (!empty($request->getJsonParams())) {
                $options['json'] = $request->getJsonParams();
            } elseif (!empty($request->getFormParams())) {
                $options['form_params'] = $request->getFormParams();
            }

            $response = $this->client->request($request->getMethod(), $request->getEndpoint(), $options);
        } catch (GuzzleException $e) {
            throw ExceptionFactory::createException($e->getMessage(), $e->getCode());
        }

        return new Response($response->getBody()->getContents(), $response->getStatusCode());
    }
}

