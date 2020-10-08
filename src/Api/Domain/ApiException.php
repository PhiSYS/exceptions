<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\ExceptionResource;

abstract class ApiException extends \DomainException implements \JsonSerializable
{
    private const PAD_LENGTH_ERROR_CODE = 3;
    private const PAD_LENGTH_RESOURCE_CODE = 2;

    private int $httpCode;
    private ExceptionResource $resource;
    private array $extraData;
    private int $apiCode;

    public function __construct(
        int $httpCode,
        ExceptionResource $resource,
        int $errorCode,
        array $extraData,
        string $message,
        ?\Throwable $previous = null
    ) {
        $apiCode = $this->buildApiCode($httpCode, $resource, $errorCode);

        parent::__construct(
            $message,
            $apiCode,
            $previous,
        );

        $this->resource = $resource;
        $this->httpCode = $httpCode;
        $this->apiCode = $apiCode;
        $this->extraData = $extraData;
    }

    public function httpCode(): int
    {
        return $this->httpCode;
    }

    public function apiCode(): int
    {
        return $this->apiCode;
    }

    public function jsonSerialize(): array
    {
        return [
            'aggregate_id' => $this->resource->resourceId(),
            'message' => $this->message,
            'error_code' => $this->apiCode,
            'extra_data' => $this->extraData,
        ];
    }

    private function buildApiCode(int $httpCode, ExceptionResource $resource, int $errorCode): int
    {
        return (int) (
            $httpCode
            . $this->stringifyCode($resource->resourceCode(), self::PAD_LENGTH_RESOURCE_CODE)
            . $this->stringifyCode($errorCode, self::PAD_LENGTH_ERROR_CODE)
        );
    }

    private function stringifyCode(int $code, int $digits): string
    {
        return \str_pad(
            (string) $code,
            $digits,
            '0',
            \STR_PAD_LEFT,
        );
    }
}
