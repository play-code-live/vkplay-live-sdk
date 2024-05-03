<?php

namespace PlayCode\VKPlayLiveSDK\Exception;

class ServiceUnavailableException extends ClientException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 503);
    }
}