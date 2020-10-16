<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\Resource;

abstract class ApiException extends \DomainException implements \JsonSerializable
{
    private const PAD_LENGTH_ERROR_CODE = 3;
    private const PAD_LENGTH_RESOURCE_CODE = 2;

    private int $statusCode;
    private array $extraData;
    private int $apiCode;

    public function __construct(
        string $message,
        array $extraData = [],
        ?\Throwable $previous = null
    ) {
        $resource = static::getResource();
        $statusCode = static::STATUS_CODE;
        $apiCode = $this->buildApiCode($statusCode, $resource, static::ERROR_CODE);

        parent::__construct(
            $message,
            $apiCode,
            $previous,
        );

        $this->statusCode = $statusCode;
        $this->apiCode = $apiCode;
        $this->extraData = $extraData;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function apiCode(): int
    {
        return $this->apiCode;
    }

    public function extraData(): array
    {
        return $this->extraData;
    }

    public function jsonSerialize(): array
    {
        return [
            'message' => $this->message,
            'error_code' => $this->apiCode,
            'extra_data' => $this->extraData,
        ];
    }

    abstract protected static function getResource(): Resource;

    private function buildApiCode(int $statusCode, Resource $resource, int $errorCode): int
    {
        return (int) (
            $statusCode
            . $this->stringifyCode($resource->resourceCode(), self::PAD_LENGTH_RESOURCE_CODE)
            . $this->stringifyCode($errorCode, self::PAD_LENGTH_ERROR_CODE)
        );
    }

    private function stringifyCode(int $code, int $digits): string
    {
        if (\strlen((string) $code) > $digits) {
            throw new \InvalidArgumentException(
                \sprintf('Codes above %d digits not allowed. Given %d.', $digits, $code),
            );
        }

        return \str_pad(
            (string) $code,
            $digits,
            '0',
            \STR_PAD_LEFT,
        );
    }
}
