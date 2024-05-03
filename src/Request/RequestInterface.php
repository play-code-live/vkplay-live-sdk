<?php

namespace PlayCode\VKPlayLiveSDK\Request;

interface RequestInterface
{
    public const METHOD_GET  = 'GET';
    public const METHOD_POST = 'POST';

    public function getEndpoint(): string;
    public function getMethod(): string;
    public function getFormParams(): array;
    public function getJsonParams(): array;
    public function getHeaders(): array;
}