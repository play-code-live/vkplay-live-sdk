<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class StreamInfoDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly int $startedAt,
        public readonly int $endedAt,
        public readonly string $previewUrl,
        public readonly SmallCategoryDTO $category,
        public readonly array $reactions,
        public readonly CountersDTO $counters,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? '',
            $data['title'] ?? '',
            $data['started_at'] ?? 0,
            $data['ended_at'] ?? 0,
            $data['preview_url'] ?? '',
            SmallCategoryDTO::fromArray($data['category'] ?? []),
            array_map(fn($r) => ReactionDTO::fromArray($r ?? []), $data['reactions'] ?? []),
            CountersDTO::fromArray($data['counters'] ?? []),
        );
    }
}