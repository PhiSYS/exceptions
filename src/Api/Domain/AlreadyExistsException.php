<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\Resource;

abstract class AlreadyExistsException extends ApiException
{
    private const STATUS_CODE = 400;

    public function __construct(Resource $resource, int $errorCode, ?\Throwable $previous = null)
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

    private function buildMessage(Resource $resource): string
    {
        return \sprintf(
            '%s already exists.',
            \ucfirst($resource->resourceName()),
        );
    }
}
