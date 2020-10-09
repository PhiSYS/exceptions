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
        $errorCode = 345;

        $expectedMessage = \sprintf(
            'Required reference %s not yet available. May be available soon, or creation is required.',
            $referenceName,
        );

        $resource = $this->createMock(Resource::class);

        $exception = new class ($resource, $errorCode, $referenceId, $referenceName, null) extends ReferenceNotYetAvailableException
        {
            public function __construct(
                Resource $resource,
                int $errorCode,
                string $referenceId,
                string $referenceName,
                ?\Throwable $previous
            ) {
                parent::__construct(
                    $resource,
                    $errorCode,
                    $referenceId,
                    $referenceName,
                    $previous,
                );
            }
        };

        self::assertEquals($expectedMessage, $exception->getMessage());
    }

    public function testExtendedExceptionShouldGenerateRightExtraData()
    {
        $referenceId = 'fe28ff0a-fc8c-460f-b205-1429b6c5732c';
        $referenceName = 'reference_name';
        $errorCode = 538;

        $resource = $this->createMock(Resource::class);

        $expectedExtraData = [
            'reference' => [
                'id' => $referenceId,
                'name' => $referenceName,
            ],
        ];

        $exception = new class ($resource, $errorCode, $referenceId, $referenceName, null) extends ReferenceNotYetAvailableException
        {
            public function __construct(
                Resource $resource,
                int $errorCode,
                string $referenceId,
                string $referenceName,
                ?\Throwable $previous
            ) {
                parent::__construct(
                    $resource,
                    $errorCode,
                    $referenceId,
                    $referenceName,
                    $previous,
                );
            }
        };

        self::assertSame($expectedExtraData, $exception->extraData());
    }
}
