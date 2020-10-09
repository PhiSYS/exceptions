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
        $referenceName = 'reference_name';
        $errorCode = 345;

        $expectedMessage = \sprintf(
            'Required reference %s not yet available. May be available soon, or creation is required.',
            $referenceName,
        );

        $reference = $this->createMock(Reference::class);
        $reference
            ->method('name')
            ->willReturn($referenceName)
        ;

        $resource = $this->createMock(Resource::class);

        $exception = new class ($resource, $errorCode, $reference, null) extends ReferenceNotYetAvailableException
        {
            public function __construct(
                Resource $resource,
                int $errorCode,
                Reference $reference,
                ?\Throwable $previous
            ) {
                parent::__construct(
                    $resource,
                    $errorCode,
                    $reference,
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

        $reference = $this->createMock(Reference::class);
        $reference
            ->method('id')
            ->willReturn($referenceId)
        ;
        $reference
            ->method('name')
            ->willReturn($referenceName)
        ;

        $resource = $this->createMock(Resource::class);

        $expectedExtraData = [
            'reference' => [
                'id' => $reference->id(),
                'name' => $reference->name(),
            ],
        ];

        $exception = new class ($resource, $errorCode, $reference, null) extends ReferenceNotYetAvailableException
        {
            public function __construct(
                Resource $resource,
                int $errorCode,
                Reference $reference,
                ?\Throwable $previous
            ) {
                parent::__construct(
                    $resource,
                    $errorCode,
                    $reference,
                    $previous,
                );
            }
        };

        self::assertSame($expectedExtraData, $exception->extraData());
    }
}
