<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class CountersDTO
{
    public function __construct(
        private int $viewers,
        private int $views
    )
    {
    }

    public function getViewers(): int
    {
        return $this->viewers;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['viewers'] ?? 0,
            $data['views'] ?? 0,
        );
    }
}