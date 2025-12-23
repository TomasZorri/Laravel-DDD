<?php

namespace Src\Catalog\Category\Domain\ValueObjects;

final readonly class CategoryName
{
    public function __construct(private string $value)
    {
        if (strlen($value) < 2) {
            throw new \InvalidArgumentException('Name too short');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
