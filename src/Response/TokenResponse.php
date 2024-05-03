<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

class TokenResponse extends JsonResponse
{
    public readonly string $accessToken;
    public readonly string $refreshToken;
    public readonly int $expiresIn;
    public readonly string $tokenType;

    protected function buildFromBody(array $data): void
    {
        $this->accessToken = $data['access_token'] ?? '';
        $this->refreshToken = $data['refresh_token'] ?? '';
        $this->expiresIn = $data['expires_in'] ?? 0;
        $this->tokenType = $data['token_type'] ?? '';
    }
}

