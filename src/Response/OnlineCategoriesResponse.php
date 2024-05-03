<?php

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\CategoryDTO;

class OnlineCategoriesResponse extends Response
{
    /** @var CategoryDTO[] */
    private array $categories;

    public function __construct(string $body, int $statusCode)
    {
        $data = json_decode($body, true)['data'] ?? [];
        foreach ($data['categories'] ?? [] as $category) {
            $this->categories[] = CategoryDTO::fromArray($category);
        }

        parent::__construct($body, $statusCode);
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}