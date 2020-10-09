<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\Domain\BusinessLogicException;
use DosFarma\Exceptions\Api\ExceptionResource;
use PHPUnit\Framework\TestCase;

final class BusinessLogicExceptionTest extends TestCase
{
    public function testExtendedExceptionShouldBeBusinessLogicException()
    {
        $resource = $this->createMock(ExceptionResource::class);
        $errorCode = 122;
        $extraData = [];
        $message = 'Exception message.';

        $exception = new class ($resource, $errorCode, $extraData, $message, null) extends BusinessLogicException
        {
            public function __construct(
                ExceptionResource $resource,
                int $errorCode,
                array $extraData,
                string $message,
                ?\Throwable $previous = null
            ) {
              parent::__construct(
                  $resource,
                  $errorCode,
                  $extraData,
                  $message,
                  $previous,
              );
            }
        };

        self::assertInstanceOf(BusinessLogicException::class, $exception);
    }
}
