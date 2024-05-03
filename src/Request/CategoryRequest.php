<?php

namespace PlayCode\VKPlayLiveSDK\Request;

class CategoryRequest extends AppAndUserAuthRequest
{
    public function __construct(
        private string $id,
        private string $clientId,
        private string $clientSecret,
        private ?string $accessToken = null,
    )
    {
        parent::__construct($this->clientId, $this->clientSecret, $this->accessToken);
    }
    public function getEndpoint(): string
    {
        return 'v1/category?category_id=' . $this->id;
    }

    public function getMethod(): string
    {
        return self::METHOD_GET;
    }
}