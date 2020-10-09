<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\ExceptionResource;

abstract class AlreadyExistsException extends ApiException
{
    private const STATUS_CODE = 400;

    public function __construct(ExceptionResource $resource, int $errorCode, ?\Throwable $previous = null)
    {
        parent::__construct(
            self::STATUS_CODE,
            $resource,
            $errorCode,
            [],
            $this->buildMessage($resource),
            $previous,
        );
    }

    private function buildMessage(ExceptionResource $resource): string
    {
        return \sprintf(
            '%s %s already exists.',
            $resource->resourceName(),
            $resource->resourceId(),
        );
    }
}
