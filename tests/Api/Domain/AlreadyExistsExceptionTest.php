<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\Resource;
use DosFarma\Exceptions\Api\Domain\AlreadyExistsException;
use PHPUnit\Framework\TestCase;

final class AlreadyExistsExceptionTest extends TestCase
{
    public function testExtendedExceptionShouldGenerateRightMessageException()
    {
        $resourceName = 'Resource Name';
        $errorCode = 25;

        $expectedMessage = \sprintf('%s already exists.', $resourceName);

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceName')
            ->willReturn($resourceName)
        ;

        $exception = new class ($resource, $errorCode, null) extends AlreadyExistsException
        {
            public function __construct(Resource $resource, int $errorCode, ?\Throwable $previous)
            {
                parent::__construct(
                    $resource,
                    $errorCode,
                    $previous,
                );
            }
        };

        self::assertEquals($expectedMessage, $exception->getMessage());
    }
}
