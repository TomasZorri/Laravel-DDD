<?php

namespace Src\Auth\User\Domain\ValueObjects;

final class UserEstado
{
    private const ALLOWED_ESTADOS = ['activo', 'inactivo'];

    public function __construct(private string $value)
    {
        if (!in_array($value, self::ALLOWED_ESTADOS)) {
            throw new \InvalidArgumentException('Invalid state');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
