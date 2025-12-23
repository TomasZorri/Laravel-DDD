<?php

namespace Src\Catalog\Product\Application\DTO;

final class ProductListResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $nombre,
        public readonly float $precio
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'precio' => $this->precio,
        ];
    }
}
