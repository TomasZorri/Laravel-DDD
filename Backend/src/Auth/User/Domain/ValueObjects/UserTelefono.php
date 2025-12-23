<?php

namespace Src\Auth\User\Domain\ValueObjects;

final class UserTelefono
{
    public function __construct(private ?string $value)
    {
    }

    public function value(): ?string
    {
        return $this->value;
    }
}
