<?php

namespace Src\Catalog\Product\Domain\ValueObjects;

final readonly class ProductNombre
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
