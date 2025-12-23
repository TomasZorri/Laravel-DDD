<?php

namespace Src\Catalog\Product\Domain\ValueObjects;

final readonly class ProductPrecio
{
    public function __construct(private float $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Precio cannot be negative');
        }
    }

    public function value(): float
    {
        return $this->value;
    }
}
