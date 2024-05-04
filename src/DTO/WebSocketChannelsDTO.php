<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class WebSocketChannelsDTO
{
    public function __construct(
        public readonly string $chat,
        public readonly string $privateChat,
        public readonly string $info,
        public readonly string $privateInfo,
        public readonly string $channelPoints,
        public readonly string $privateChannelPoints,
        public readonly string $limitedChat,
        public readonly string $privateLimitedChat,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['chat'] ?? '',
            $data['private_chat'] ?? '',
            $data['info'] ?? '',
            $data['private_info'] ?? '',
            $data['channel_points'] ?? '',
            $data['private_channel_points'] ?? '',
            $data['limited_chat'] ?? '',
            $data['limited_private_chat'] ?? ''
        );
    }
}
