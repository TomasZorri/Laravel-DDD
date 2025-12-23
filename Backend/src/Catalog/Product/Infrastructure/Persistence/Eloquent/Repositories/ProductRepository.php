<?php

namespace Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Catalog\Product\Domain\Contracts\ProductRepositoryInterface;
use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Models\ProductModel;
use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Mappers\ProductMapper;
use Src\Catalog\Product\Domain\Entities\Product;
use Src\Catalog\Product\Domain\ValueObjects\ProductId;

final class ProductRepository implements ProductRepositoryInterface
{
    public function save(Product $product): Product
    {
        $model = ProductModel::create(ProductMapper::toEloquent($product));

        return ProductMapper::toDomain($model);
    }

    public function findById(ProductId $id): ?Product
    {
        $model = ProductModel::find($id->value());

        return $model
            ? ProductMapper::toDomain($model)
            : null;
    }

    public function findAll(array $filters = []): array
    {
        return ProductModel::where($filters)
            ->get()
            ->map(fn(ProductModel $model) => ProductMapper::toDomain($model))
            ->toArray();
    }

    public function update(Product $product): Product
    {
        $model = ProductModel::find($product->id());

        if (!$model) {
            throw new \RuntimeException('Product not found');
        }

        $model->update(ProductMapper::toEloquent($product));

        return ProductMapper::toDomain($model);
    }

    public function delete(ProductId $id): void
    {
        ProductModel::destroy($id->value());
    }
}