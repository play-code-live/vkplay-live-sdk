<?php

namespace PlayCode\Tests\VKPlayLiveSDK;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PlayCode\VKPlayLiveSDK\Client;
use GuzzleHttp\Client as HttpClient;

class ClientExtended extends Client
{
    public function __construct(array $responses)
    {
        parent::__construct('clientId', 'clientSecret');

        $this->applyResponses($responses);
    }

    private function applyResponses(array $responses): void
    {
        $mock = new MockHandler($responses);

        $handlerStack = HandlerStack::create($mock);
        $this->client = new HttpClient(['handler' => $handlerStack]);
    }
}