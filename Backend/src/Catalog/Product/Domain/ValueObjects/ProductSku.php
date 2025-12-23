<?php

namespace Src\Catalog\Product\Domain\ValueObjects;

final readonly class ProductSku
{
    public function __construct(private string $value)
    {
        if (strlen($value) < 3) {
            throw new \InvalidArgumentException('SKU too short');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
