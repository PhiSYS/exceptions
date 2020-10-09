<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\Resource;

abstract class ReferenceNotYetAvailableException extends ApiException
{
    private const STATUS_CODE = 409;

    public function __construct(
        Resource $resource,
        int $errorCode,
        Reference $reference,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            self::STATUS_CODE,
            $resource,
            $errorCode,
            $this->buildExtraData($reference),
            $this->buildMessage($reference),
            $previous,
        );
    }

    private function buildExtraData(Reference $reference): array
    {
        return [
            'reference' => [
                'id' => $reference->id(),
                'name' => $reference->name(),
            ],
        ];
    }

    private function buildMessage(Reference $reference): string
    {
        return \sprintf(
            'Required reference %s not yet available. May be available soon, or creation is required.',
            $reference->name(),
        );
    }
}
