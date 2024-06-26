<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class ChannelRequest extends AppAndUserAuthRequest
{
    public function __construct(
        private string $channelUrl,
        private string $clientId,
        private string $clientSecret,
        private ?string $accessToken = null,
    )
    {
        parent::__construct($this->clientId, $this->clientSecret, $this->accessToken);
    }

    public function getEndpoint(): string
    {
        return 'v1/channel?' . http_build_query(['channel_url' => $this->channelUrl]);
    }

    public function getMethod(): string
    {
        return self::METHOD_GET;
    }
}