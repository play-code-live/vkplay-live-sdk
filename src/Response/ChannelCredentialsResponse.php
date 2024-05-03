<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

class ChannelCredentialsResponse extends JsonResponse
{
    public readonly string $url;
    public readonly string $token;

    protected function buildFromBody(array $data): void
    {
        $this->url = $data['data']['url'] ?? '';
        $this->token = $data['data']['token'] ?? '';
    }
}