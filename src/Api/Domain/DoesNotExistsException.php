<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\ExceptionResource;

class DoesNotExistsException extends ApiException
{
    private const STATUS_CODE = 404;

    public function __construct(int $errorCode, ExceptionResource $resource, ?\Throwable $previous = null)
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
            '%s %s does not exists',
            $resource->resourceName(),
            $resource->resourceId(),
        );
    }
}
