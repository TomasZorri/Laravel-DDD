<?php

namespace Src\Catalog\Category\Application\DTO;

use Src\Catalog\Category\Domain\Entities\Category;

final class CategoryWithProductsResponse
{
    public function __construct(
        private Category $category,
        private array $products
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->category->id(),
            'nombre' => $this->category->nombre()->value(),
            'descripcion' => $this->category->descripcion()->value(),
            'estado' => $this->category->estado()->value(),
            'productos' => array_map(function ($product) {
                return [
                    'id' => $product->id(),
                    'nombre' => $product->nombre()->value(),
                    'descripcion' => $product->descripcion()->value(),
                    'precio' => $product->precio()->value(),
                ];
            }, $this->products)
        ];
    }
}
