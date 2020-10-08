<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\ExceptionResource;
use DosFarma\Exceptions\Api\Domain\AlreadyExistsException;
use PHPUnit\Framework\TestCase;

final class AlreadyExistsExceptionTest extends TestCase
{
    public function testExtendedExceptionShouldGenerateRightMessageException()
    {
        $resourceName = 'Resource Name';
        $resourceId = '687bd66a-12b7-4a2b-9a77-107105bce3db';
        $errorCode = 25;

        $expectedMessage = \sprintf('%s %s already exists', $resourceName, $resourceId);

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceName')
            ->willReturn($resourceName)
        ;
        $resource
            ->method('resourceId')
            ->willReturn($resourceId)
        ;

        $exception = new class ($resource, $errorCode, null) extends AlreadyExistsException
        {
            public function __construct(ExceptionResource $resource, int $errorCode, ?\Throwable $previous)
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
