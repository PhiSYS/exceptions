<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\Domain\Reference;
use DosFarma\Exceptions\Api\Domain\ReferenceNotYetAvailableException;
use DosFarma\Exceptions\Api\Resource;
use PHPUnit\Framework\TestCase;

final class ReferenceNotYetAvailableExceptionTest extends TestCase
{
    public function testExtendedExceptionShouldGenerateRightMessageException()
    {
        $referenceId = '981fc42b-148e-4b76-bfe0-c06a6d320433';
        $referenceName = 'reference_name';

        $expectedMessage = \sprintf(
            'Required reference %s not yet available. May be available soon, or creation is required.',
            $referenceName,
        );

        $resource = $this->createMock(Resource::class);

        $exception = new class ($resource, $referenceId, $referenceName, null) extends ReferenceNotYetAvailableException
        {
            protected const ERROR_CODE = 345;
            private static Resource $resource;

            public function __construct(
                Resource $resource,
                string $referenceId,
                string $referenceName,
                ?\Throwable $previous
            ) {
                self::$resource = $resource;

                parent::__construct(
                    $referenceId,
                    $referenceName,
                    $previous,
                );
            }

            protected static function getResource(): Resource
            {
                return self::$resource;
            }
        };

        self::assertEquals($expectedMessage, $exception->getMessage());
    }

    public function testExtendedExceptionShouldGenerateRightExtraData()
    {
        $referenceId = 'fe28ff0a-fc8c-460f-b205-1429b6c5732c';
        $referenceName = 'reference_name';

        $resource = $this->createMock(Resource::class);

        $expectedExtraData = [
            'reference' => [
                'id' => $referenceId,
                'name' => $referenceName,
            ],
        ];

        $exception = new class ($resource, $referenceId, $referenceName, null) extends ReferenceNotYetAvailableException
        {
            protected const ERROR_CODE = 538;
            private static Resource $resource;

            public function __construct(
                Resource $resource,
                string $referenceId,
                string $referenceName,
                ?\Throwable $previous
            ) {
                self::$resource = $resource;

                parent::__construct(
                    $referenceId,
                    $referenceName,
                    $previous,
                );
            }

            protected static function getResource(): Resource
            {
                return self::$resource;
            }
        };

        self::assertSame($expectedExtraData, $exception->extraData());
    }
}
