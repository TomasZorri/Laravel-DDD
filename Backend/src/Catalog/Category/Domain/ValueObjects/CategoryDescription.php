<?php

namespace Src\Catalog\Category\Domain\ValueObjects;

final readonly class CategoryDescription
{
    public function __construct(private ?string $value)
    {
    }

    public function value(): ?string
    {
        return $this->value;
    }
}
