<?php

namespace PlayCode\VKPlayLiveSDK\Response;

class ChannelCredentialsResponse extends Response
{
    private string $url;
    private string $token;

    public function __construct(string $body, int $statusCode)
    {
        $data = json_decode($body, true)['data'] ?? [];
        $this->url = $data['url'] ?? '';
        $this->token = $data['token'] ?? '';

        parent::__construct($body, $statusCode);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}