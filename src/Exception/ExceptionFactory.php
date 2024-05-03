<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK\Exception;

class ExceptionFactory
{
    public static function createException(string $message, int $code): ClientException
    {
        return match ($code) {
            400 => new InvalidParamException($message),
            401 => new UnauthorizedException($message),
            403 => new ForbiddenException($message),
            404 => new NotFoundException($message),
            422 => new UnprocessableEntityException($message),
            429 => new RateLimitExceededException($message),
            500 => new InternalServerErrorException($message),
            503 => new ServiceUnavailableException($message),
            default => new ClientException($message, $code),
        };
    }
}