<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\CategoryDTO;

class CategoriesResponse extends JsonResponse
{
    /** @var CategoryDTO[] */
    public array $categories;

    protected function buildFromBody(array $data): void
    {
        foreach ($data['data']['categories'] ?? [] as $category) {
            $this->categories[] = CategoryDTO::fromArray($category);
        }
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}