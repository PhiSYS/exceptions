<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api;

interface ExceptionResource
{
    public function resourceName(): string;

    public function resourceCode(): int;

    public function resourceId(): string;
}
