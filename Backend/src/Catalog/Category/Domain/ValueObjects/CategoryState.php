<?php

namespace Src\Catalog\Category\Domain\ValueObjects;

final readonly class CategoryState
{
    private const ACTIVO = 'activo';
    private const INACTIVO = 'inactivo';

    private function __construct(private string $value)
    {
    }

    public static function from(string $value): self
    {
        // Validamos que el string sea uno de los permitidos
        if (!in_array($value, [self::ACTIVO, self::INACTIVO], true)) {
            throw new \InvalidArgumentException("El estado <$value> no es un estado de Category válido.");
        }

        return new self($value);
    }

    public static function activo(): self
    {
        return new self(self::ACTIVO);
    }

    public static function inactivo(): self
    {
        return new self(self::INACTIVO);
    }

    public function isActivo(): bool
    {
        return $this->value === self::ACTIVO;
    }

    public function value(): string
    {
        return $this->value;
    }
}