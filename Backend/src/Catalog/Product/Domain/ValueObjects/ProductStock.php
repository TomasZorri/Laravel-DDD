<?php

namespace Src\Catalog\Product\Domain\ValueObjects;

final readonly class ProductStock
{
    public function __construct(private int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Stock cannot be negative');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
