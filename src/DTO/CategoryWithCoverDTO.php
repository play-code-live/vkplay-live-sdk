<?php

namespace PlayCode\VKPlayLiveSDK\DTO;

class CategoryWithCoverDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $type,
        public readonly string $coverUrl
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
        );
    }
}