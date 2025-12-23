<?php

namespace Src\Catalog\Product\Domain\ValueObjects;

final readonly class ProductDescripcion
{
    public function __construct(private ?string $value)
    {
    }

    public function value(): ?string
    {
        return $this->value;
    }
}
