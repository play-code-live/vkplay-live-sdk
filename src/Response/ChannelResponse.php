<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\ChannelDTO;

class ChannelResponse extends JsonResponse
{
    public readonly ChannelDTO $channel;

    protected function buildFromBody(array $data): void
    {
        $this->channel = ChannelDTO::fromArray($data['data'] ?? []);
    }
}