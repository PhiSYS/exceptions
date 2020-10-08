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
        $httpCodeString = '404';
        $resourceCodeString = '01';
        $errorCodeString = '043';

        $expectedApiCode = (integer) \sprintf('%s%s%s', $httpCodeString, $resourceCodeString, $errorCodeString);

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((integer) $resourceCodeString)
        ;

        $exception =
            new class ((integer) $httpCodeString, (integer) $errorCodeString, $resource) extends ApiException
            {
                public function __construct(int $httpCode, int $errorCode, ExceptionResource $resource)
                {
                    parent::__construct(
                        $httpCode,
                        $resource,
                        $errorCode,
                        [],
                        'Exception message',
                    );
                }
            }
        ;

        self::assertSame($expectedApiCode, $exception->apiCode());
    }

    public function testExtendedClassShouldReturnHttpCodeAndApiCode()
    {
        $httpCodeString = '404';
        $resourceCodeString = '01';
        $errorCodeString = '043';

        $expectedApiCode = (integer) \sprintf('%s%s%s', $httpCodeString, $resourceCodeString, $errorCodeString);
        $expectedHttpCode = (integer) $httpCodeString;

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((integer) $resourceCodeString)
        ;

        $exception =
            new class ((integer) $httpCodeString, (integer) $errorCodeString, $resource) extends ApiException
            {
                public function __construct(int $httpCode, int $errorCode, ExceptionResource $resource)
                {
                    parent::__construct(
                        $httpCode,
                        $resource,
                        $errorCode,
                        [],
                        'Exception message',
                    );
                }
            }
        ;

        self::assertSame($expectedHttpCode, $exception->httpCode());
        self::assertSame($expectedApiCode, $exception->apiCode());
    }

    public function testExtendedClassShouldHaveRightSerialization()
    {

        $httpCodeString = '404';
        $resourceCodeString = '01';
        $resourceId = '687bd66a-12b7-4a2b-9a77-107105bce3db';
        $errorCodeString = '043';
        $apiCode = (integer) \sprintf('%s%s%s', $httpCodeString, $resourceCodeString, $errorCodeString);
        $message = 'Exception message';

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((integer) $resourceCodeString)
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

        $exception =
            new class ((integer) $httpCodeString, (integer) $errorCodeString, $resource, $message) extends ApiException
            {
                public function __construct(int $httpCode, int $errorCode, ExceptionResource $resource, string $message)
                {
                    parent::__construct(
                        $httpCode,
                        $resource,
                        $errorCode,
                        [],
                        $message,
                    );
                }
            }
        ;

        self::assertSame($expectedSerialization, \json_encode($exception));
    }
}
