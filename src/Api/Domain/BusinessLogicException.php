<?php
declare(strict_types=1);

namespace PhiSYS\Exceptions\Api\Domain;

use PhiSYS\Exceptions\Api\Resource;

abstract class BusinessLogicException extends ApiException
{
    private const STATUS_CODE = 400;

    public function __construct(
        Resource $resource,
        int $errorCode,
        array $extraData,
        string $message,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            self::STATUS_CODE,
            $resource,
            $errorCode,
            $extraData,
            $message,
            $previous,
        );
    }
}
