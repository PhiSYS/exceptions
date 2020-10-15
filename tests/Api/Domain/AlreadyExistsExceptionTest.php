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
        $resourceName = 'resource name';

        $expectedMessage = \sprintf('%s already exists.', \ucfirst($resourceName));

        $resource = $this->createMock(Resource::class);
        $resource
            ->method('resourceName')
            ->willReturn($resourceName)
        ;

        $exception = new class ($resource, null) extends AlreadyExistsException
        {
            protected const ERROR_CODE = 25;
            private Resource $resource;

            public function __construct(Resource $resource, ?\Throwable $previous)
            {
                $this->resource = $resource;

                parent::__construct($previous);
            }

            protected function getResource(): Resource
            {
                return $this->resource;
            }
        };

        self::assertEquals($expectedMessage, $exception->getMessage());
    }
}
