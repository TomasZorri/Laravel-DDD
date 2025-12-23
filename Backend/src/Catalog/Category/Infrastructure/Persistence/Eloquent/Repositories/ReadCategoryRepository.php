<?php

namespace Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Catalog\Category\Application\Contracts\CategoryReadRepositoryInterface;
use Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Models\CategoryModel;
use Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Mappers\CategoryMapper;
use Src\Catalog\Category\Domain\Entities\Category;
use Src\Catalog\Category\Domain\ValueObjects\CategoryId;

final class ReadCategoryRepository implements CategoryReadRepositoryInterface
{
    public function findById(CategoryId $id): ?Category
    {
        $model = CategoryModel::find($id->value());

        return $model
            ? CategoryMapper::toDomain($model)
            : null;
    }

    public function findBySlug(string $slug): array
    {
        return [];
        /*
        return CategoryModel::where($filters)
            ->get()
            ->map(fn(CategoryModel $model) => CategoryMapper::toDomain($model))
            ->toArray();
        */
    }

    public function findAll(array $filters = []): array
    {
        return CategoryModel::where($filters)
            ->get()
            ->map(fn(CategoryModel $model) => CategoryMapper::toDomain($model))
            ->toArray();
    }
}