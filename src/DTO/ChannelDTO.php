<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class ChannelDTO
{
    public function __construct(
        private ChannelInfoDTO $channelInfo,
        private OwnerDTO $owner,
        private StreamInfoDTO $streamInfo,
    )
    {
    }

    public function getChannelInfo(): ChannelInfoDTO
    {
        return $this->channelInfo;
    }

    public function getOwner(): OwnerDTO
    {
        return $this->owner;
    }

    public function getStreamInfo(): StreamInfoDTO
    {
        return $this->streamInfo;
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