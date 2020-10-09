<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

use DosFarma\Exceptions\Api\Resource;

final class UnavailableResource implements Resource
{
    private string $name;
    private int $code;

    public function __construct(string $name, int $code)
    {
        $this->name = $name;
        $this->code = $code;
    }

    public function resourceName(): string
    {
        return $this->name;
    }

    public function resourceCode(): int
    {
        return $this->code;
    }
}
