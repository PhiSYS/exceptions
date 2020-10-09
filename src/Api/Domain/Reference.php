<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

interface Reference
{
    public function id(): string;

    public function name(): string;
}
