<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\CategoryDTO;

class CategoryResponse extends JsonResponse
{
    public readonly CategoryDTO $category;

    protected function buildFromBody(array $data): void
    {
        $this->category = CategoryDTO::fromArray($data['data']['category'] ?? []);
    }
}