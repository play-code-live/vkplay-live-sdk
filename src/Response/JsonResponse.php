<?php

namespace PlayCode\VKPlayLiveSDK\Response;

use PlayCode\VKPlayLiveSDK\Exception\ClientException;
use PlayCode\VKPlayLiveSDK\Exception\ParseJsonException;

abstract class JsonResponse extends Response
{
    /**
     * @throws ClientException
     */
    public function __construct(string $body, int $statusCode)
    {
        parent::__construct('', $statusCode);

        $this->buildFromBody($this->getJsonBodyDecoded($body));
    }

    abstract protected function buildFromBody(array $data): void;

    /**
     * @throws ParseJsonException
     */
    protected function getJsonBodyDecoded(string $body): array
    {
        return json_decode($body, true) ?? throw new ParseJsonException();
    }
}