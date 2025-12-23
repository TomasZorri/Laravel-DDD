<?php

namespace Src\Auth\User\Domain\ValueObjects;

final class UserPassword
{
    public function __construct(private string $value)
    {
        if (strlen($value) < 6) {
            throw new \InvalidArgumentException('Password too short');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
