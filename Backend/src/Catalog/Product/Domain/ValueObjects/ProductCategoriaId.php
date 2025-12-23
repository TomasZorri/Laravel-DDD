<?php

namespace Src\Catalog\Product\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class ProductCategoriaId
{
    public function __construct(private int $value)
    {
        if ($value < 1) {
            throw new InvalidArgumentException('ProductCategoryId must be positive');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public static function from(int $value): self
    {
        return new self($value);
    }
}
