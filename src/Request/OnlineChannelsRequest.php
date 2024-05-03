<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class OnlineChannelsRequest extends ChannelRequest
{
    private const LIMIT_MAX = 200;
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private int $limit,
        private string $categoryId = '',
        private string $categoryType = '',
        private ?string $accessToken = null
    )
    {
        parent::__construct('', $this->clientId, $this->clientSecret, $this->accessToken);
    }

    public function getEndpoint(): string
    {
        return 'v1/catalog/online_channels?' . http_build_query($this->getQuery());
    }

    protected function getQuery(): array
    {
        $query = [
            'limit' => min($this->limit, self::LIMIT_MAX),
        ];
        if (!empty($this->categoryId)) {
            $query['category_id'] = $this->categoryId;
        }
        if (!empty($this->categoryType)) {
            $query['category_type'] = $this->categoryType;
        }

        return $query;
    }
}