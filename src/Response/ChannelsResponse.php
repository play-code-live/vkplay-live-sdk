<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\ChannelDTO;

class ChannelsResponse extends JsonResponse
{
    /**
     * @var ChannelDTO[]
     */
    public readonly array $channels;

    protected function buildFromBody(array $data): void
    {
        $this->channels = array_map(fn($channel) => ChannelDTO::fromArray($channel), $data['data']['channels'] ?? []);
    }
}