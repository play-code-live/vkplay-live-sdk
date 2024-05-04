<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\DTO\CategoryDTO;

class CategoriesResponse extends JsonResponse
{
    /** @var CategoryDTO[] */
    public readonly array $categories;

    protected function buildFromBody(array $data): void
    {
        $this->categories = array_map(fn (array $category) => CategoryDTO::fromArray($category), $data['data']['categories']);
    }
}