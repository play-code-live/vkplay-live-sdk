<?php

declare(strict_types = 1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class ReactionDTO
{
    public const TYPE_HEART = 'heart';

    public function __construct(
        public readonly string $type,
        public readonly int $count
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['type'] ?? self::TYPE_HEART,
            $data['count'] ?? 0
        );
    }
}