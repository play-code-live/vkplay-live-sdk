<?php

namespace PlayCode\VKPlayLiveSDK\Exception;

class RateLimitExceededException extends ClientException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 429);
    }
}