<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\Resource;

abstract class ReferenceNotYetAvailableException extends ApiException
{
    protected const STATUS_CODE = 409;

    public function __construct(
        string $referenceId,
        string $referenceName,
        ?\Throwable $previous = null
    ) {
        $resource = static::getResource();
        parent::__construct(
            $this->buildMessage($referenceName),
            $this->buildExtraData($referenceId, $referenceName),
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
