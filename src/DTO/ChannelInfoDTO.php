<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class ChannelInfoDTO
{
    public function __construct(
        public string $url,
        public string $coverUrl,
        public string $status,
        public int $subscribers,
        public WebSocketChannelsDTO $webSocketChannels
    )
    {
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