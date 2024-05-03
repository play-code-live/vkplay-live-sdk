<?php

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\ChannelDTO;

class ChannelResponse extends Response
{
    private ChannelDTO $channel;

    public function __construct(string $body, int $statusCode)
    {
        $data = json_decode($body, true);
        $this->channel = ChannelDTO::fromArray($data['data'] ?? []);

        parent::__construct('', $statusCode);
    }

    public function getChannel(): ChannelDTO
    {
        return $this->channel;
    }
}