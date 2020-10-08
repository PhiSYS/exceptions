<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\ExceptionResource;
use DosFarma\Exceptions\Api\Domain\ApiException;
use PHPUnit\Framework\TestCase;

final class ApiExceptionTest extends TestCase
{
    public function testExtendedExceptionShouldGenerateRightApiCode()
    {
        $statusCodeString = '404';
        $resourceCodeString = '01';
        $errorCodeString = '043';

        $expectedApiCode = (int) \sprintf('%s%s%s', $statusCodeString, $resourceCodeString, $errorCodeString);

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((int) $resourceCodeString)
        ;

        $exception = new class ((int) $statusCodeString, (int) $errorCodeString, $resource) extends ApiException
        {
            public function __construct(int $statusCode, int $errorCode, ExceptionResource $resource)
            {
                parent::__construct(
                    $statusCode,
                    $resource,
                    $errorCode,
                    [],
                    'Exception message',
                );
            }
        };

        self::assertSame($expectedApiCode, $exception->apiCode());
    }

    public function testExtendedClassShouldReturnstatusCodeAndApiCode()
    {
        $statusCodeString = '404';
        $resourceCodeString = '01';
        $errorCodeString = '043';

        $expectedApiCode = (int) \sprintf('%s%s%s', $statusCodeString, $resourceCodeString, $errorCodeString);
        $expectedStatusCode = (int) $statusCodeString;

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((int) $resourceCodeString)
        ;

        $exception = new class ((int) $statusCodeString, (int) $errorCodeString, $resource) extends ApiException
        {
            public function __construct(int $statusCode, int $errorCode, ExceptionResource $resource)
            {
                parent::__construct(
                    $statusCode,
                    $resource,
                    $errorCode,
                    [],
                    'Exception message',
                );
            }
        };

        self::assertSame($expectedStatusCode, $exception->statusCode());
        self::assertSame($expectedApiCode, $exception->apiCode());
    }

    public function testExtendedClassShouldHaveRightSerialization()
    {
        $statusCodeString = '404';
        $resourceCodeString = '01';
        $resourceId = '687bd66a-12b7-4a2b-9a77-107105bce3db';
        $errorCodeString = '043';
        $apiCode = (int) \sprintf('%s%s%s', $statusCodeString, $resourceCodeString, $errorCodeString);
        $message = 'Exception message';

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((int) $resourceCodeString)
        ;
        $resource
            ->method('resourceId')
            ->willReturn($resourceId)
        ;

        $expectedSerialization = \json_encode([
            'aggregate_id' => $resourceId,
            'message' => $message,
            'error_code' => $apiCode,
            'extra_data' => [],
        ]);

        $exception = new class ((int) $statusCodeString, (int) $errorCodeString, $resource, $message) extends ApiException
        {
            public function __construct(int $statusCode, int $errorCode, ExceptionResource $resource, string $message)
            {
                parent::__construct(
                    $statusCode,
                    $resource,
                    $errorCode,
                    [],
                    $message,
                );
            }
        };

        self::assertSame($expectedSerialization, \json_encode($exception));
    }

    public function testExtendedClassShouldThrowExceptionWhenErrorCodeExceedsThreeDigits()
    {
        $statusCode = 404;
        $errorCode = 1043;

        $resource = $this->createMock(ExceptionResource::class);

        $this->expectException(\InvalidArgumentException::class);

        new class ((int) $statusCode, (int) $errorCode, $resource) extends ApiException
        {
            public function __construct(int $statusCode, int $errorCode, ExceptionResource $resource)
            {
                parent::__construct(
                    $statusCode,
                    $resource,
                    $errorCode,
                    [],
                    'Exception message',
                );
            }
        };
    }

    public function testExtendedClassShouldThrowExceptionWhenResourceCodeExceedsTwoDigits()
    {
        $statusCode = 404;
        $resourceCode = 131;
        $errorCode = 143;

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceCode')
            ->willReturn($resourceCode)
        ;

        $this->expectException(\InvalidArgumentException::class);

        new class ((int) $statusCode, (int) $errorCode, $resource) extends ApiException
        {
            public function __construct(int $statusCode, int $errorCode, ExceptionResource $resource)
            {
                parent::__construct(
                    $statusCode,
                    $resource,
                    $errorCode,
                    [],
                    'Exception message',
                );
            }
        };
    }
}
