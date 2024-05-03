<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class OnlineCategoriesRequest extends OnlineChannelsRequest
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private int $limit,
        private string $categoryType = '',
        private ?string $accessToken = null
    )
    {
        parent::__construct($this->clientId, $this->clientSecret, $this->limit, '', $this->categoryType, $this->accessToken);
    }

    public function getEndpoint(): string
    {
        return 'v1/catalog/online_categories?' . http_build_query($this->getQuery());
    }
}