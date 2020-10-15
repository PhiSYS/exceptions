<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\Resource;

abstract class DoesNotExistsException extends ApiException
{
    protected const STATUS_CODE = 404;

    public function __construct(?\Throwable $previous = null)
    {
        $resource = static::getResource();

        parent::__construct(
            $this->buildMessage($resource),
            [],
            $previous,
        );
    }

    private function buildMessage(Resource $resource): string
    {
        return \sprintf(
            '%s does not exists.',
            \ucfirst($resource->resourceName()),
        );
    }
}
