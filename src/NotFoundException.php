<?php
declare(strict_types=1);

namespace DosFarma\Exceptions;

class NotFoundException extends \UnexpectedValueException implements ApiException
{
    use ApiExceptionTrait;

    private const HTTP_CODE = 404;

    public function __construct(int $errorCode, ExceptionResource $resource, array $extraData, ?\Throwable $throwable)
    {
        $apiCode = $this->buildApiCode(self::HTTP_CODE, $resource, $errorCode);
        $message = $this->buildMessage($resource);

        parent::__construct(
            $message,
            $apiCode,
            $throwable,
        );

        $this->errorCode = $errorCode;
        $this->httpCode = self::HTTP_CODE;
        $this->apiCode = $apiCode;
        $this->extraData = $extraData;
    }

    private function buildMessage(ExceptionResource $resource): string
    {
        return \sprintf(
            '%s %s not found.',
            $resource->resourceName(),
            $resource->resourceId(),
        );
    }
}
