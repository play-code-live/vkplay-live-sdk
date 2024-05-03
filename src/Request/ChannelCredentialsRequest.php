<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class ChannelCredentialsRequest implements RequestInterface
{
    public function __construct(
        private string $channelUrl,
        private string $accessToken
    )
    {
    }

    public function getEndpoint(): string
    {
        return 'v1/channel/credentials?channel_url=' . $this->channelUrl;
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
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ];
    }
}