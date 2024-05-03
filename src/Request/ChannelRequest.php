<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class ChannelRequest implements RequestInterface
{
    public function __construct(
        private string $channelUrl,
        private string $clientId,
        private string $clientSecret,
        private ?string $accessToken = null,
    )
    {
    }

    public function getEndpoint(): string
    {
        return 'v1/channel?' . http_build_query(['channel_url' => $this->channelUrl]);
    }

    public function getMethod(): string
    {
        return self::METHOD_GET;
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