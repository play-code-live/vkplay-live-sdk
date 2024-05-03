<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class WebSocketChannelsDTO
{
    public function __construct(
        private string $chat,
        private string $privateChat,
        private string $info,
        private string $privateInfo,
        private string $channelPoints,
        private string $privateChannelPoints,
        private string $limitedChat,
        private string $privateLimitedChat,
    )
    {
    }

    public function getChat(): string
    {
        return $this->chat;
    }

    public function getPrivateChat(): string
    {
        return $this->privateChat;
    }

    public function getInfo(): string
    {
        return $this->info;
    }

    public function getPrivateInfo(): string
    {
        return $this->privateInfo;
    }

    public function getChannelPoints(): string
    {
        return $this->channelPoints;
    }

    public function getPrivateChannelPoints(): string
    {
        return $this->privateChannelPoints;
    }

    public function getLimitedChat(): string
    {
        return $this->limitedChat;
    }

    public function getPrivateLimitedChat(): string
    {
        return $this->privateLimitedChat;
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
            $data['private_limited_chat'] ?? ''
        );
    }
}
