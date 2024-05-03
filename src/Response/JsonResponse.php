<?php

namespace PlayCode\VKPlayLiveSDK\Response;

abstract class JsonResponse extends Response
{
    public function __construct(string $body, int $statusCode)
    {
        parent::__construct('', $statusCode);

        $this->buildFromBody($this->getJsonBodyDecoded($body));
    }

    abstract protected function buildFromBody(array $data): void;

    protected function getJsonBodyDecoded(string $body): array
    {
        return json_decode($body, true);
    }
}