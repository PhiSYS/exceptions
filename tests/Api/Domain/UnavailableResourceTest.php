<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Tests\Api\Domain;

use DosFarma\Exceptions\Api\Domain\UnavailableResource;
use PHPUnit\Framework\TestCase;

final class UnavailableResourceTest extends TestCase
{
    public function testUnavailableResourceShouldReturnProperties()
    {
        $name = 'unavailable resource';
        $code = 34;

        $resource = new UnavailableResource($name, $code);

        self::assertEquals($name, $resource->resourceName());
        self::assertSame($code, $resource->resourceCode());
    }
}
