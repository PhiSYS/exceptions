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
        $statusCode = 404;
        $resourceCode = 1;
        $errorCode = 43;

        $expectedApiCode = (int) \sprintf('%3d%02d%03d', $statusCode, $resourceCode, $errorCode);

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceCode')
            ->willReturn($resourceCode)
        ;

        $exception = new class ($resource) extends ApiException
        {
            protected const STATUS_CODE = 404;
            protected const ERROR_CODE = 43;
            private static Resource $resource;

            public function __construct(Resource $resource)
            {
                self::$resource = $resource;

                parent::__construct('Exception message.');
            }

            protected static function getResource(): Resource
            {
                return self::$resource;
            }
        };

        self::assertSame($expectedApiCode, $exception->apiCode());
    }

    public function testExtendedClassShouldReturnStatusCodeAndApiCodeAndExtraData()
    {
        $statusCode = 404;
        $resourceCode = 1;
        $errorCode = 43;

        $expectedApiCode = (int) \sprintf('%3d%02d%03d', $statusCode, $resourceCode, $errorCode);
        $expectedExtraData = ['some_key' => 'some value'];

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((int) $resourceCode)
        ;

        $exception = new class (
            $resource,
            $expectedExtraData
        ) extends ApiException
        {
            protected const STATUS_CODE = 404;
            protected const ERROR_CODE = 43;
            private static Resource $resource;

            public function __construct(Resource $resource, array $extraData)
            {
                self::$resource = $resource;

                parent::__construct(
                    'Exception message.',
                    $extraData,
                );
            }

            protected static function getResource(): Resource
            {
                return self::$resource;
            }
        };

        self::assertSame($statusCode, $exception->statusCode());
        self::assertSame($expectedApiCode, $exception->apiCode());
        self::assertSame($expectedExtraData, $exception->extraData());
    }

    public function testExtendedClassShouldHaveRightSerialization()
    {
        $statusCode = 404;
        $resourceCode = 1;
        $errorCode = 43;
        $apiCode = (int) \sprintf('%3d%02d%03d', $statusCode, $resourceCode, $errorCode);
        $message = 'Exception message.';

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((int) $resourceCode)
        ;

        $expectedSerialization = \json_encode([
            'message' => $message,
            'error_code' => $apiCode,
            'extra_data' => [],
        ]);

        $exception = new class ($resource, $message) extends ApiException
        {
            protected const STATUS_CODE = 404;
            protected const ERROR_CODE = 43;
            private static Resource $resource;

            public function __construct(Resource $resource, string $message)
            {
                self::$resource = $resource;

                parent::__construct(
                    $message,
                    [],
                );
            }

            protected static function getResource(): Resource
            {
                return self::$resource;
            }
        };

        self::assertSame($expectedSerialization, \json_encode($exception));
    }

    public function testExtendedClassShouldThrowExceptionWhenErrorCodeExceedsThreeDigits()
    {
        $resource = $this->createMock(Resource::class);

        $this->expectException(\InvalidArgumentException::class);

        new class ($resource) extends ApiException
        {
            protected const STATUS_CODE = 404;
            protected const ERROR_CODE = 1043;
            private static Resource $resource;

            public function __construct(Resource $resource)
            {
                self::$resource = $resource;

                parent::__construct(
                    'Exception message.',
                    [],
                );
            }

            protected static function getResource(): Resource
            {
                return self::$resource;
            }
        };
    }

    public function testExtendedClassShouldThrowExceptionWhenResourceCodeExceedsTwoDigits()
    {
        $resourceCode = 131;

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceCode')
            ->willReturn($resourceCode)
        ;

        $this->expectException(\InvalidArgumentException::class);

        new class ($resource) extends ApiException
        {
            protected const STATUS_CODE = 404;
            protected const ERROR_CODE = 143;
            private static Resource $resource;

            public function __construct(Resource $resource)
            {
                self::$resource = $resource;

                parent::__construct(
                    'Exception message.',
                    [],
                );
            }

            protected static function getResource(): Resource
            {
                return self::$resource;
            }
        };
    }
}
