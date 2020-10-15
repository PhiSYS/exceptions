<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\Resource;

abstract class BusinessLogicException extends ApiException
{
    protected const STATUS_CODE = 400;

    public function __construct(
        string $message,
        array $extraData = [],
        ?\Throwable $previous = null
    ) {
        $resource = static::getResource();

        parent::__construct(
            $message,
            $extraData,
            $previous,
        );
    }
}
