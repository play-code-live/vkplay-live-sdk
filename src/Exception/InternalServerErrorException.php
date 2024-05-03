<?php

namespace PlayCode\VKPlayLiveSDK\Exception;

class InternalServerErrorException extends ClientException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 500);
    }
}