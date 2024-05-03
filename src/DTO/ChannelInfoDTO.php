<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class ChannelInfoDTO
{
    public function __construct(
        private string $url,
        private string $coverUrl,
        private string $status,
        private int $subscribers,
        private WebSocketChannelsDTO $webSocketChannels
    )
    {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getWebSocketChannels(): WebSocketChannelsDTO
    {
        return $this->webSocketChannels;
    }

    public function getSubscribers(): int
    {
        return $this->subscribers;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['url'] ?? '',
            $data['cover_url'] ?? '',
            $data['status'] ?? '',
            $data['counters']['subscribers'] ?? 0,
            WebSocketChannelsDTO::fromArray($data['web_socket_channels'] ?? []),
        );
    }
}