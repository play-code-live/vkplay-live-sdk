<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class CategoryDTO
{
    public function __construct(
        private string $id,
        private string $title,
        private string $type,
        private string $coverUrl,
        private int $viewers,
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function getViewers(): int
    {
        return $this->viewers;
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