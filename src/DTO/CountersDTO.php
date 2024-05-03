<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class CountersDTO
{
    public function __construct(
        public readonly int $viewers,
        public readonly int $views
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['viewers'] ?? 0,
            $data['views'] ?? 0,
        );
    }
}