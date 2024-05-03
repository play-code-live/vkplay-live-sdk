<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use PlayCode\VKPlayLiveSDK\Exception\ClientException;
use PlayCode\VKPlayLiveSDK\Request\RefreshTokenRequest;
use PlayCode\VKPlayLiveSDK\Request\RequestInterface;
use PlayCode\VKPlayLiveSDK\Request\RevokeRequest;
use PlayCode\VKPlayLiveSDK\Request\AccessTokenRequest;
use PlayCode\VKPlayLiveSDK\Response\Response;
use PlayCode\VKPlayLiveSDK\Response\ResponseInterface;
use PlayCode\VKPlayLiveSDK\Response\TokenResponse;

class Client
{
    private const API_HOST = 'https://api.vkplay.live/';
    private string $clientId;
    private string $clientSecret;
    private HttpClient $client;

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
        $response = $this->sendRequest($request);
        if (!$response->isSuccess()) {
            throw new ClientException('Failed to get access token', $response->getStatusCode());
        }

        return TokenResponse::createFromResponse($response);
    }

    /**
     * @throws ClientException
     */
    public function refreshToken(string $refreshToken): TokenResponse
    {
        $request = new RefreshTokenRequest($refreshToken, $this->clientId, $this->clientSecret);
        $response = $this->sendRequest($request);
        if (!$response->isSuccess()) {
            throw new ClientException('Failed to refresh token', $response->getStatusCode());
        }

        return TokenResponse::createFromResponse($response);
    }

    /**
     * @throws ClientException
     */
    public function revokeToken(string $token, string $tokenTypeHint = RevokeRequest::HINT_ACCESS_TOKEN): void
    {
        $request = new RevokeRequest($this->clientId, $this->clientSecret, $token, $tokenTypeHint);
        $response = $this->sendRequest($request);
        if (!$response->isSuccess()) {
            throw new ClientException('Failed to refresh token', $response->getStatusCode());
        }
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
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }

        return new Response($response->getBody()->getContents(), $response->getStatusCode());
    }
}

