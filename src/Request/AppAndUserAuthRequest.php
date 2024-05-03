<?php

namespace PlayCode\VKPlayLiveSDK\Request;

abstract class AppAndUserAuthRequest implements RequestInterface
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private ?string $accessToken = null,
    )
    {
    }

    public function getFormParams(): array
    {
        return [];
    }

    public function getJsonParams(): array
    {
        return [];
    }

    public function getHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if ($this->accessToken !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        } else {
            $headers['Authorization'] = 'Basic ' . base64_encode($this->clientId.':'.$this->clientSecret);
        }
        return $headers;
    }
}