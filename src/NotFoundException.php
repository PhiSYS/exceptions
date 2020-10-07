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

        parent::__construct(
            '',
            $apiCode,
            $throwable,
        );

        $this->errorCode = $errorCode;
        $this->httpCode = self::HTTP_CODE;
        $this->apiCode = $apiCode;
        $this->extraData = $extraData;
        $this->message = $this->buildMessage($resource);
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
