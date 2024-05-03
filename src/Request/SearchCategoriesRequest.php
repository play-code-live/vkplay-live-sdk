<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Request;

class SearchCategoriesRequest extends OnlineChannelsRequest
{
    private string $clientId;
    private ?string $accessToken = null;
    private string $clientSecret;
    private string $query;
    private int $limit;
    private string $categoryType;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $query,
        string $categoryType,
        int $limit = self::LIMIT_MAX,
        ?string $accessToken = null
    )
    {
        $this->categoryType = $categoryType;
        $this->limit = $limit;
        $this->query = $query;
        $this->clientSecret = $clientSecret;
        $this->accessToken = $accessToken;
        $this->clientId = $clientId;
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
            $query['type'] = $this->categoryType;
        }

        return $query;
    }
}