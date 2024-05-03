<?php

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\ChannelDTO;

class OnlineChannelsListResponse extends Response
{
    /** @var ChannelDTO[] */
    private array $channels;

    public function __construct(string $body, int $statusCode)
    {
        $data = json_decode($body, true)['data'] ?? [];
        foreach ($data['channels'] ?? [] as $channel) {
            $this->channels[] = ChannelDTO::fromArray($channel);
        }

        parent::__construct($body, $statusCode);
    }

    public function getChannels(): array
    {
        return $this->channels;
    }
}