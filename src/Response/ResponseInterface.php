<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Response;

interface ResponseInterface
{
    public function getBody(): string;
    public function getStatusCode(): int;
    public function isSuccess(): bool;
}
