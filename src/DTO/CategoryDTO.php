<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\DTO;

class CategoryDTO
{
    public const TYPE_GAME = 'game';

    public function __construct(
        private string $id,
        private string $title,
        private string $type
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

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? '',
            $data['title'] ?? '',
            $data['type'] ?? ''
        );
    }
}