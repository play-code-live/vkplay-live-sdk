<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class OwnerDTO
{
    public function __construct(
        private int $id,
        private string $nick,
        private int $nickColor,
        private string $avatarUrl,
        private bool $isVerifiedStreamer
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNick(): string
    {
        return $this->nick;
    }

    public function getNickColor(): int
    {
        return $this->nickColor;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function isVerifiedStreamer(): bool
    {
        return $this->isVerifiedStreamer;
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