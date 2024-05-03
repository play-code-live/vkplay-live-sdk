<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class StreamInfoDTO
{
    public function __construct(
        private string $id,
        private string $title,
        private int $startedAt,
        private int $endedAt,
        private string $previewUrl,
        private CategoryDTO $category,
        private array $reactions,
        private CountersDTO $counters,
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

    public function getStartedAt(): int
    {
        return $this->startedAt;
    }

    public function getEndedAt(): int
    {
        return $this->endedAt;
    }

    public function getPreviewUrl(): string
    {
        return $this->previewUrl;
    }

    public function getCategory(): CategoryDTO
    {
        return $this->category;
    }

    public function getReactions(): array
    {
        return $this->reactions;
    }

    public function getCounters(): CountersDTO
    {
        return $this->counters;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? '',
            $data['title'] ?? '',
            $data['started_at'] ?? 0,
            $data['ended_at'] ?? 0,
            $data['preview_url'] ?? '',
            CategoryDTO::fromArray($data['category'] ?? []),
            $data['reactions'] ?? [],
            CountersDTO::fromArray($data['counters'] ?? []),
        );
    }
}