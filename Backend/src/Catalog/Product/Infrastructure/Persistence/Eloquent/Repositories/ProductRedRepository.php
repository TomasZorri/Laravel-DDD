<?php

namespace Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Catalog\Product\Application\Contracts\ProductReadRepositoryInterface;
use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Models\ProductModel;
use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Mappers\ProductMapper;

final class ProductRedRepository implements ProductReadRepositoryInterface
{

    public function findAll(array $filters = []): array
    {
        return ProductModel::where($filters)
            ->get()
            ->map(fn(ProductModel $model) => ProductMapper::toDomain($model))
            ->toArray();
    }

    public function findByCategory(int $categoryId, array $filters = []): array
    {
        return ProductModel::where('categoria_id', $categoryId)
            ->where($filters)
            ->get()
            ->map(fn(ProductModel $model) => ProductMapper::toDomain($model))
            ->toArray();
    }
}