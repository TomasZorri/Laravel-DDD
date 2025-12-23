<?php

namespace Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Catalog\Category\Domain\Contracts\CategoryRepositoryInterface;
use Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Models\CategoryModel;
use Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Mappers\CategoryMapper;
use Src\Catalog\Category\Domain\Entities\Category;
use Src\Catalog\Category\Domain\ValueObjects\CategoryId;

final class CategoryRepository implements CategoryRepositoryInterface
{
    public function save(Category $Category): Category
    {
        $model = CategoryModel::create(CategoryMapper::toEloquent($Category));

        return CategoryMapper::toDomain($model);
    }

    public function findById(CategoryId $id): ?Category
    {
        $model = CategoryModel::find($id->value());

        return $model
            ? CategoryMapper::toDomain($model)
            : null;
    }

    public function findAll(array $filters = []): array
    {
        return CategoryModel::where($filters)
            ->get()
            ->map(fn(CategoryModel $model) => CategoryMapper::toDomain($model))
            ->toArray();
    }

    public function update(Category $category): Category
    {
        $model = CategoryModel::find($category->id());
        if (!$model) {
            throw new \RuntimeException('Category not found');
        }

        $model->update(
            CategoryMapper::toEloquent($category)
        );

        return CategoryMapper::toDomain($model);
    }

    public function delete(CategoryId $id): void
    {
        CategoryModel::destroy($id->value());
    }
}