<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\Resource;
use DosFarma\Exceptions\Api\Domain\DoesNotExistsException;
use PHPUnit\Framework\TestCase;

final class DoesNotExistsExceptionTest extends TestCase
{
    public function testExtendedExceptionShouldGenerateRightMessageException()
    {
        $resourceName = 'resource name';
        $errorCode = 43;

        $expectedMessage = \sprintf('%s does not exists.', \ucfirst($resourceName));

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceName')
            ->willReturn($resourceName)
        ;

        $exception = new class ($resource, $errorCode, null) extends DoesNotExistsException
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
