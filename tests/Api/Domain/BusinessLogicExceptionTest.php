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
            private Resource $resource;

            public function __construct(
                Resource $resource,
                array $extraData,
                string $message,
                ?\Throwable $previous = null
            ) {
                $this->resource = $resource;

                parent::__construct($message, $extraData, $previous);
            }

            protected function getResource(): Resource
            {
                return $this->resource;
            }
        };

        self::assertInstanceOf(BusinessLogicException::class, $exception);
    }
}
