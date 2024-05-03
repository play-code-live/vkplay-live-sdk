<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Request;

class TokenRequest implements RequestInterface
{
    public const GRANT_TYPE_TOKEN = 'authorization_code';
    public const GRANT_TYPE_REFRESH = 'refresh_token';

    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private string $redirectUri,
        private string $grantType = self::GRANT_TYPE_TOKEN,
        private string $code = '',
    ) {
    }

    public function getEndpoint(): string
    {
        return 'oauth/server/token';
    }

    public function getMethod(): string
    {
        return RequestInterface::METHOD_POST;
    }

    public function getFormParams(): array
    {
        return [
            'code' => $this->code,
            'grant_type' => $this->grantType,
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