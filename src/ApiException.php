<?php
declare(strict_types=1);

namespace DosFarma\Exceptions;

interface ApiException extends \JsonSerializable
{
    public function httpCode(): int;

    public function apiCode(): int;
}
