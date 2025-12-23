<?php

namespace Src\Auth\User\Domain\ValueObjects;

final class UserNombre
{
    public function __construct(private string $value)
    {
        if (strlen($value) < 2) {
            throw new \InvalidArgumentException('Nombre too short');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
