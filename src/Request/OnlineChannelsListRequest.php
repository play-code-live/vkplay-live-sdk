<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class OnlineChannelsListRequest implements RequestInterface
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private int $limit,
        private string $categoryId = '',
        private string $categoryType = '',
        private ?string $accessToken = null
    )
    {
    }

    public function getEndpoint(): string
    {
        return 'v1/catalog/online_channels?' . http_build_query($this->getQuery());
    }

    private function getQuery(): array
    {
        $query = [
            'limit' => $this->limit,
        ];
        if (!empty($this->categoryId)) {
            $query['category_id'] = $this->categoryId;
        }
        if (!empty($this->categoryType)) {
            $query['category_type'] = $this->categoryType;
        }

        return $query;
    }

    public function getMethod(): string
    {
        return RequestInterface::METHOD_GET;
    }

    public function getFormParams(): array
    {
        return [];
    }

    public function getJsonParams(): array
    {
        return [];
    }

    public function getHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if ($this->accessToken !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        } else {
            $headers['Authorization'] = 'Basic ' . base64_encode($this->clientId.':'.$this->clientSecret);
        }
        return $headers;
    }
}