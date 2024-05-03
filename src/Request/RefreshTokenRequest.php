<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Request;

class RefreshTokenRequest extends AccessTokenRequest
{
    private const GRANT_TYPE = 'refresh_token';

    public function __construct(
        private string $refreshToken,
        private string $clientId,
        private string $clientSecret,
    ) {
        parent::__construct("", $this->clientId, $this->clientSecret, "");
    }

    public function getFormParams(): array
    {
        return [
            'refresh_token' => $this->refreshToken,
            'grant_type' => self::GRANT_TYPE,
        ];
    }
}