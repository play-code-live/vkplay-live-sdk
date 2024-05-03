<?php

declare(strict_types = 1);

namespace PlayCode\VKPlayLiveSDK\Request;

class RevokeRequest implements RequestInterface
{
    public const HINT_ACCESS_TOKEN = 'access_token';
    public const HINT_REFRESH_TOKEN = 'refresh_token';

    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private string $token,
        private string $hint = self::HINT_ACCESS_TOKEN
    )
    {
    }
    public function getEndpoint(): string
    {
        return 'oauth/server/revoke';
    }

    public function getMethod(): string
    {
        return RequestInterface::METHOD_POST;
    }

    public function getFormParams(): array
    {
        return [
            'token' => $this->token,
            'token_type_hint' => $this->hint,
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
            'Authorization' => 'Basic ' . base64_encode($this->clientId.':'.$this->clientSecret),
        ];
    }
}