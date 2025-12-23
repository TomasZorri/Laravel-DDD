<?php

namespace Src\Catalog\Category\Application\Command;

final class UpdateCategoryCommand
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $nombre,
        public readonly ?string $descripcion,
        public readonly ?string $estado
    ) {
    }
}
