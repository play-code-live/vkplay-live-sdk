<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class SearchCategoriesRequest extends OnlineChannelsRequest
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private string $query,
        private int $limit,
        private string $categoryType = '',
        private ?string $accessToken = null
    )
    {
        parent::__construct($this->clientId, $this->clientSecret, $this->limit, '', $this->categoryType, $this->accessToken);
    }

    public function getEndpoint(): string
    {
        return 'v1/category/search?' . http_build_query($this->getQuery());
    }

    protected function getQuery(): array
    {
        $query = [
            'limit' => min($this->limit, self::LIMIT_MAX),
            'query' => $this->query,
        ];
        if (!empty($this->categoryType)) {
            $query['category_type'] = $this->categoryType;
        }

        return $query;
    }
}