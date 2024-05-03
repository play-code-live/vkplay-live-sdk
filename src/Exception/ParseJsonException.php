<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Exception;

class ParseJsonException extends ClientException
{
    public function __construct()
    {
        parent::__construct('Response body is not valid JSON.', 401);
    }
}