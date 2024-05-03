<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

class TokenResponse extends Response
{
    private string $accessToken;
    private string $refreshToken;
    private int $expiresIn;
    private string $tokenType;

    public function __construct(string $body, int $statusCode)
    {
        $data = json_decode($body, true);
        $this->accessToken = $data['access_token'] ?? '';
        $this->refreshToken = $data['refresh_token'] ?? '';
        $this->expiresIn = $data['expires_in'] ?? 0;
        $this->tokenType = $data['token_type'] ?? '';
        
        parent::__construct("", $statusCode);
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }
}

