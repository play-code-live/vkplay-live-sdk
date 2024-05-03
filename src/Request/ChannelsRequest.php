<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class ChannelsRequest implements RequestInterface
{
    private const CHANNELS_COUNT_LIMIT = 100;

    public function __construct(
        private array $channelUrls,
        private string $clientId,
        private string $clientSecret,
        private ?string $accessToken = null,
    )
    {
    }

    public function getEndpoint(): string
    {
        return 'v1/channels';
    }

    public function getMethod(): string
    {
        return self::METHOD_POST;
    }

    public function getFormParams(): array
    {
        return [];
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