<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\Domain\Reference;
use PHPUnit\Framework\TestCase;

final class ReferenceTest extends TestCase
{
    public function testReferenceShouldReturnProperties()
    {
        $expectedName = 'reference_name';
        $expectedId = '87ff129f-a5bd-45f4-a204-9bd952f098f4';

        $reference = new Reference($expectedId, $expectedName);

        self::assertEquals($expectedId, $reference->id());
        self::assertEquals($expectedName, $reference->name());
    }
}
