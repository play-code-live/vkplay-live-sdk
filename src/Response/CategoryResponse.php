<?php

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\CategoryDTO;

class CategoryResponse extends Response
{
    private CategoryDTO $category;

    public function __construct(string $body, int $statusCode)
    {
        $data = json_decode($body, true)['data'] ?? [];
        $this->category = CategoryDTO::fromArray($data['category'] ?? []);

        parent::__construct('', $statusCode);
    }

    public function getCategory(): CategoryDTO
    {
        return $this->category;
    }
}