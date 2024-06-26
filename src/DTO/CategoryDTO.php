<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class CategoryDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $type,
        public readonly string $coverUrl,
        public readonly int $viewers,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? '',
            $data['title'] ?? '',
            $data['type'] ?? '',
            $data['cover_url'] ?? '',
            $data['counters']['viewers'] ?? 0,
        );
    }
}