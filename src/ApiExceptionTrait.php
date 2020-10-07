<?php
declare(strict_types=1);

namespace DosFarma\Exceptions;

trait ApiExceptionTrait
{
    private int $httpCode;
    private ExceptionResource $resource;
    private int $errorCode;
    private array $extraData;
    private int $apiCode;

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
            'message' => (string) $this->message,
            'error_code' => $this->apiCode,
            'extra_data' => $this->extraData,
        ];
    }

    private function buildApiCode(int $httpCode, ExceptionResource $resource, int $errorCode): int
    {
        return (integer)(
            $httpCode
            . $this->stringifyCode($resource->resourceCode(), 2)
            . $this->stringifyCode($errorCode, 3)
        );
    }

    private function stringifyCode(int $code, int $digits): string
    {
        if (\strlen((string)$code) > $digits) {
            throw new \Exception(
                \sprintf('Codes above %d digits not allowed. Given %d', $digits, $code)
            );
        }

        return \str_pad(
            (string)$code,
            $digits,
            '0',
            STR_PAD_LEFT,
        );
    }
}
