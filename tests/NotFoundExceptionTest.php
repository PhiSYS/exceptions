<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests;

use DosFarma\Exceptions\ExceptionResource;
use DosFarma\Exceptions\NotFoundException;
use PHPUnit\Framework\TestCase;

final class NotFoundExceptionTest extends TestCase
{
    public function testExtendedExceptionShouldGenerateRightApiCodeException()
    {
        $httpCodeString = '404';
        $resourceCodeString = '01';
        $errorCodeString = '043';

        $expectedApiCode = \sprintf('%s%s%s', $httpCodeString, $resourceCodeString, $errorCodeString);

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceCode')
            ->willReturn((integer) $resourceCodeString)
        ;

        $exception = new class ((integer) $errorCodeString, $resource, null) extends NotFoundException
            {
                public function __construct(int $errorCode, ExceptionResource $resource, ?\Throwable $throwable)
                {
                    parent::__construct(
                        $errorCode,
                        $resource,
                        [],
                        $throwable,
                    );
                }
            }
        ;

        self::assertEquals($expectedApiCode, $exception->apiCode());
    }

    public function testExtendedExceptionShouldGenerateRightMessageException()
    {
        $resourceName = 'Resource Name';
        $resourceId = '687bd66a-12b7-4a2b-9a77-107105bce3db';
        $errorCodeString = '043';

        $expectedMessage = \sprintf('%s %s not found.', $resourceName, $resourceId);

        $resource = $this->createMock(ExceptionResource::class);
        $resource
            ->method('resourceName')
            ->willReturn($resourceName)
        ;
        $resource
            ->method('resourceId')
            ->willReturn($resourceId)
        ;

        $exception = new class ((integer) $errorCodeString, $resource, null) extends NotFoundException
            {
                public function __construct(int $errorCode, ExceptionResource $resource, ?\Throwable $throwable)
                {
                    parent::__construct(
                        $errorCode,
                        $resource,
                        [],
                        $throwable,
                    );
                }
            }
        ;

        self::assertEquals($expectedMessage, $exception->getMessage());
    }
}
