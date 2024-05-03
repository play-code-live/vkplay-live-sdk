<?php

namespace PlayCode\VKPlayLiveSDK\Exception;

class InvalidParamException extends ClientException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 400);
    }
}