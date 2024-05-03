<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class ChannelDTO
{
    public function __construct(
        public readonly ChannelInfoDTO $channelInfo,
        public readonly OwnerDTO $owner,
        public readonly StreamInfoDTO $streamInfo,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            ChannelInfoDTO::fromArray($data['channel'] ?? []),
            OwnerDTO::fromArray($data['owner'] ?? []),
            StreamInfoDTO::fromArray($data['stream'] ?? [])
        );
    }
}