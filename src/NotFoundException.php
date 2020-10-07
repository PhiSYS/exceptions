<?php
declare(strict_types=1);

namespace DosFarma\Exceptions;

class NotFoundException extends \UnexpectedValueException
{
    private const HTTP_CODE = 404;

    private int $apiCode;

    public function __construct(int $error, ExceptionResource $resource, ?\Throwable $throwable)
    {
        $apiCode = $this->code($error, $resource);

        parent::__construct(
            $this->message($resource),
            $apiCode,
            $throwable,
        );

        $this->apiCode = $apiCode;
    }

    public function httpCode(): int
    {
        return self::HTTP_CODE;
    }

    public function apiCode(): int
    {
        return $this->apiCode;
    }

    private function message(ExceptionResource $resource): string
    {
        return \sprintf(
            '%s %s not found.',
            $resource->resourceName(),
            $resource->resourceId(),
        );
    }

    private function code(int $errorCode, ExceptionResource $resource): int
    {
        return (integer)(
            self::HTTP_CODE
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