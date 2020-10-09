<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\Resource;
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

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((int) $resourceCodeString)
        ;

        $exception = new class ((int) $statusCodeString, $resource, (int) $errorCodeString) extends ApiException
        {
            public function __construct(int $statusCode, Resource $resource, int $errorCode)
            {
                parent::__construct(
                    $statusCode,
                    $resource,
                    $errorCode,
                    [],
                    'Exception message.',
                );
            }
        };

        self::assertSame($expectedApiCode, $exception->apiCode());
    }

    public function testExtendedClassShouldReturnStatusCodeAndApiCodeAndExtraData()
    {
        $statusCodeString = '404';
        $resourceCodeString = '01';
        $errorCodeString = '043';

        $expectedApiCode = (int) \sprintf('%s%s%s', $statusCodeString, $resourceCodeString, $errorCodeString);
        $expectedStatusCode = (int) $statusCodeString;
        $expectedExtraData = ['some_key' => 'some value'];

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((int) $resourceCodeString)
        ;

        $exception = new class (
            (int) $statusCodeString,
            $resource,
            (int) $errorCodeString,
            $expectedExtraData
        ) extends ApiException {
            public function __construct(int $statusCode, Resource $resource, int $errorCode, array $extraData)
            {
                parent::__construct(
                    $statusCode,
                    $resource,
                    $errorCode,
                    $extraData,
                    'Exception message.',
                );
            }
        };

        self::assertSame($expectedStatusCode, $exception->statusCode());
        self::assertSame($expectedApiCode, $exception->apiCode());
        self::assertSame($expectedExtraData, $exception->extraData());
    }

    public function testExtendedClassShouldHaveRightSerialization()
    {
        $statusCodeString = '404';
        $resourceCodeString = '01';
        $errorCodeString = '043';
        $apiCode = (int) \sprintf('%s%s%s', $statusCodeString, $resourceCodeString, $errorCodeString);
        $message = 'Exception message.';

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((int) $resourceCodeString)
        ;

        $expectedSerialization = \json_encode([
            'message' => $message,
            'error_code' => $apiCode,
            'extra_data' => [],
        ]);

        $exception = new class ((int) $statusCodeString, $resource, (int) $errorCodeString, $message) extends ApiException
        {
            public function __construct(int $statusCode, Resource $resource, int $errorCode, string $message)
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

        $resource = $this->createMock(Resource::class);

        $this->expectException(\InvalidArgumentException::class);

        new class ((int) $statusCode, $resource, (int) $errorCode) extends ApiException
        {
            public function __construct(int $statusCode, Resource $resource, int $errorCode)
            {
                parent::__construct(
                    $statusCode,
                    $resource,
                    $errorCode,
                    [],
                    'Exception message.',
                );
            }
        };
    }

    public function testExtendedClassShouldThrowExceptionWhenResourceCodeExceedsTwoDigits()
    {
        $statusCode = 404;
        $resourceCode = 131;
        $errorCode = 143;

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceCode')
            ->willReturn($resourceCode)
        ;

        $this->expectException(\InvalidArgumentException::class);

        new class ((int) $statusCode, $resource, (int) $errorCode) extends ApiException
        {
            public function __construct(int $statusCode, Resource $resource, int $errorCode)
            {
                parent::__construct(
                    $statusCode,
                    $resource,
                    $errorCode,
                    [],
                    'Exception message.',
                );
            }
        };
    }
}
