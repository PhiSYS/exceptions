<?php
declare(strict_types=1);

namespace PhiSYS\Exceptions\Api\Domain;

use PhiSYS\Exceptions\Api\Resource;

abstract class ReferenceNotYetAvailableException extends ApiException
{
    private const STATUS_CODE = 409;

    public function __construct(
        Resource $resource,
        int $errorCode,
        string $referenceId,
        string $referenceName,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            self::STATUS_CODE,
            $resource,
            $errorCode,
            $this->buildExtraData($referenceId, $referenceName),
            $this->buildMessage($referenceName),
            $previous,
        );
    }

    private function buildExtraData(string $referenceId, string $referenceName): array
    {
        return [
            'reference' => [
                'id' => $referenceId,
                'name' => $referenceName,
            ],
        ];
    }

    private function buildMessage(string $referenceName): string
    {
        return \sprintf(
            'Required reference %s not yet available. May be available soon, or creation is required.',
            $referenceName,
        );
    }
}
