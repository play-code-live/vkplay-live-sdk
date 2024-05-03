<?php

namespace PlayCode\VKPlayLiveSDK\Exception;

class UnauthorizedException extends ClientException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 401);
    }
}