<?php

namespace PlayCode\VKPlayLiveSDK\DTO;

class SmallCategoryDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $type,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? '',
            $data['title'] ?? '',
            $data['type'] ?? '',
        );
    }
}