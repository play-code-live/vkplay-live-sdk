<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Request;

class AccessTokenRequest implements RequestInterface
{
    private const GRANT_TYPE = 'authorization_code';

    public function __construct(
        private string $code,
        private string $clientId,
        private string $clientSecret,
        private string $redirectUri,
    ) {
    }

    public function getEndpoint(): string
    {
        return 'https://api.vkplay.live/oauth/server/token';
    }

    public function getMethod(): string
    {
        return RequestInterface::METHOD_POST;
    }

    public function getFormParams(): array
    {
        return [
            'code' => $this->code,
            'grant_type' => self::GRANT_TYPE,
            'redirect_uri' => $this->redirectUri,
        ];
    }

    public function getJsonParams(): array
    {
        return [];
    }

    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
        ];
    }
}