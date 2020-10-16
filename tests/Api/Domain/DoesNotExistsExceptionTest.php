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

        $expectedMessage = \sprintf('%s does not exists.', \ucfirst($resourceName));

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceName')
            ->willReturn($resourceName)
        ;

        $exception = new class ($resource, null) extends DoesNotExistsException
        {
            protected const ERROR_CODE = 43;
            private static Resource $resource;

            public function __construct(Resource $resource, ?\Throwable $previous)
            {
                self::$resource = $resource;

                parent::__construct($previous);
            }

            protected static function getResource(): Resource
            {
                return self::$resource;
            }
        };

        self::assertEquals($expectedMessage, $exception->getMessage());
    }
}
