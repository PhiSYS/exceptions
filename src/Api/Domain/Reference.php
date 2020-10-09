<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

interface Reference
{
    public function referenceId(): string;

    public function referenceName(): string;
}
