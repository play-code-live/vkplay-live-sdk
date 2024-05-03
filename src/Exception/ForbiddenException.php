<?php

namespace PlayCode\VKPlayLiveSDK\Exception;

class ForbiddenException extends ClientException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 403);
    }
}