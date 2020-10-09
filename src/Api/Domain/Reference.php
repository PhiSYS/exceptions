<?php
declare(strict_types=1);

namespace DosFarma\Exceptions\Api\Domain;

final class Reference
{
    private string $id;
    private string $name;

    public function __construct(string $value, string $name)
    {
        $this->id = $value;
        $this->name = $name;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
