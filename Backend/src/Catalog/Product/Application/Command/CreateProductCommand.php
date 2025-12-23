<?php

namespace Src\Catalog\Product\Application\Command;

final class CreateProductCommand
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $descripcion,
        public readonly float $precio,
        public readonly int $stock,
        public readonly string $sku,
        public readonly int $categoriaId,
        public readonly string $estado
    ) {
    }
}
