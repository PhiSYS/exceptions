<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\Domain\BusinessLogicException;
use DosFarma\Exceptions\Api\Resource;
use PHPUnit\Framework\TestCase;

final class BusinessLogicExceptionTest extends TestCase
{
    public function testExtendedExceptionShouldBeBusinessLogicException()
    {
        $resource = $this->createMock(Resource::class);
        $extraData = [];
        $message = 'Exception message.';

        $exception = new class ($resource, $extraData, $message, null) extends BusinessLogicException
        {
            protected const ERROR_CODE = 122;
            private static Resource $resource;

            public function __construct(
                Resource $resource,
                array $extraData,
                string $message,
                ?\Throwable $previous = null
            ) {
                self::$resource = $resource;

                parent::__construct($message, $extraData, $previous);
            }

            protected static function getResource(): Resource
            {
                return self::$resource;
            }
        };

        self::assertInstanceOf(BusinessLogicException::class, $exception);
    }
}
