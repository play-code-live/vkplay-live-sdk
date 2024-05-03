<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class ChannelsRequest extends AppAndUserAuthRequest
{
    private const CHANNELS_COUNT_LIMIT = 100;

    public function __construct(
        private array $channelUrls,
        private string $clientId,
        private string $clientSecret,
        private ?string $accessToken = null,
    )
    {
        parent::__construct($this->clientId, $this->clientSecret, $this->accessToken);
    }

    public function getEndpoint(): string
    {
        return 'v1/channels';
    }

    public function getMethod(): string
    {
        return self::METHOD_POST;
    }

    public function getJsonParams(): array
    {
        return [
            'channels' => array_map(
                fn($url) => ['url' => $url],
                array_slice($this->channelUrls, 0, self::CHANNELS_COUNT_LIMIT)
            ),
        ];
    }
}