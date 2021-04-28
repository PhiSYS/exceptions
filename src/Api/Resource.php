<?php
declare(strict_types=1);

namespace PhiSYS\Exceptions\Api;

interface Resource
{
    public function resourceName(): string;

    public function resourceCode(): int;
}
