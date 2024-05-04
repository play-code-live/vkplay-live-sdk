<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\CategoryWithCoverDTO;

class CategoryResponse extends JsonResponse
{
    public readonly CategoryWithCoverDTO $category;

    protected function buildFromBody(array $data): void
    {
        $this->category = CategoryWithCoverDTO::fromArray($data['data']['category'] ?? []);
    }
}