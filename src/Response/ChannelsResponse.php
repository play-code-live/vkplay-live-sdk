<?php

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\ChannelDTO;

class ChannelsResponse extends Response
{
    /**
     * @var ChannelDTO[]
     */
    private array $channels;

    public function __construct(string $body, int $statusCode)
    {
        $data = json_decode($body, true)['data'] ?? [];
        $this->channels = array_map(fn($channel) => ChannelDTO::fromArray($channel), $data['channels'] ?? []);

        parent::__construct('', $statusCode);
    }

    /**
     * @return ChannelDTO[]
     */
    public function getChannels(): array
    {
        return $this->channels;
    }
}