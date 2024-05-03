<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

class Response implements ResponseInterface
{
    private string $body;
    private int $statusCode;

    public function __construct(string $body, int $statusCode)
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function isSuccess(): bool
    {
        return $this->getStatusCode() >= 200 && $this->getStatusCode() < 300;
    }
}

