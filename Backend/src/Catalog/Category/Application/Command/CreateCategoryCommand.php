<?php

namespace Src\Catalog\Category\Application\Command;

final class CreateCategoryCommand
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $descripcion,
        public readonly string $estado
    ) {
    }
}
