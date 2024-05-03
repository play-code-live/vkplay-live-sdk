<?php

namespace PlayCode\VKPlayLiveSDK\Exception;

class UnprocessableEntityException extends ClientException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 422);
    }
}