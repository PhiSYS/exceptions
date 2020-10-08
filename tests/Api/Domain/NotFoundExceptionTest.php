<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\ExceptionResource;
use DosFarma\Exceptions\Api\Domain\NotFoundException;
use PHPUnit\Framework\TestCase;

final class NotFoundExceptionTest extends TestCase
{
    public function testExtendedExceptionShouldGenerateRightMessageException()
    {
        $resourceName = 'Resource Name';
        $resourceId = '687bd66a-12b7-4a2b-9a77-107105bce3db';
        $errorCode = 43;

        $expectedMessage = \sprintf('%s %s not found', $resourceName, $resourceId);

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceName')
            ->willReturn($resourceName)
        ;
        $resource
            ->method('resourceId')
            ->willReturn($resourceId)
        ;

        $exception = new class ($errorCode, $resource, null) extends NotFoundException
        {
            public function __construct(int $errorCode, ExceptionResource $resource, ?\Throwable $previous)
            {
                parent::__construct(
                    $errorCode,
                    $resource,
                    $previous,
                );
            }
        }

        ;

        self::assertEquals($expectedMessage, $exception->getMessage());
    }
}
