<?php

namespace Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Mappers;

use Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Models\CategoryModel;
use Src\Catalog\Category\Domain\Entities\Category;

final class CategoryMapper
{
    public static function toEloquent(Category $Category): array
    {
        return [
            'nombre' => $Category->nombre()->value(),
            'descripcion' => $Category->descripcion()->value(),
            'estado' => $Category->estado()->value(),
        ];
    }

    public static function toDomain(CategoryModel $model): Category
    {
        return new Category(
            $model->id,
            $model->nombre,
            $model->descripcion,
            $model->estado
        );
    }
}