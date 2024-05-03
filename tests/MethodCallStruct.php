<?php

declare(strict_types=1);

namespace PlayCode\Tests\VKPlayLiveSDK;

use PlayCode\VKPlayLiveSDK\Client;

class MethodCallStruct
{
    public function __construct(
        public readonly string $method,
        public readonly array $args,
        public readonly Client $client,
        public readonly array $expectedProperties,
        public readonly ?string $expectedExceptionClass
    )
    {
    }
}
