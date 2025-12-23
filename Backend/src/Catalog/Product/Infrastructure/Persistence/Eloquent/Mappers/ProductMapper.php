<?php

namespace Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Mappers;

use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Models\ProductModel;
use Src\Catalog\Product\Domain\Entities\Product;

final class ProductMapper
{
    public static function toEloquent(Product $product): array
    {
        return [
            'id' => $product->id(),
            'nombre' => $product->nombre()->value(),
            'descripcion' => $product->descripcion()->value(),
            'precio' => $product->precio()->value(),
            'stock' => $product->stock()->value(),
            'sku' => $product->sku()->value(),
            'categoria_id' => $product->categoriaId()->value(),
            'estado' => $product->estado()->value(),
        ];
    }

    public static function toDomain(ProductModel $model): Product
    {
        return new Product(
            $model->id,
            $model->nombre,
            $model->descripcion,
            $model->precio,
            $model->stock,
            $model->sku,
            $model->categoria_id,
            $model->estado
        );
    }
}