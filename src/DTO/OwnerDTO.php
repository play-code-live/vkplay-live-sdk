<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class OwnerDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $nick,
        public readonly int $nickColor,
        public readonly string $avatarUrl,
        public readonly bool $isVerifiedStreamer
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            $data['nick'] ?? '',
            $data['nick_color'] ?? 0,
            $data['avatar_url'] ?? '',
            $data['is_verified_streamer'] ?? false
        );
    }
}