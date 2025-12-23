<?php

namespace Src\Catalog\Category\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class CategoryId
{
    public function __construct(private int $value)
    {
        $this->validate($value);
    }

    private function validate(int $id): void
    {
        if ($id < 1) {
            throw new InvalidArgumentException(
                sprintf('<%s> no permite el valor <%s>. Debe ser un entero positivo.', self::class, $id)
            );
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