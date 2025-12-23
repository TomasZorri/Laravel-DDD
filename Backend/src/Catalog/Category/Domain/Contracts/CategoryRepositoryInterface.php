<?php

namespace Src\Catalog\Category\Domain\Contracts;

use Src\Catalog\Category\Domain\Entities\Category;
use Src\Catalog\Category\Domain\ValueObjects\CategoryId;

interface CategoryRepositoryInterface
{
    public function save(Category $Category): Category;
    public function findById(CategoryId $id): ?Category;
    public function findAll(array $filters = []): array;
    public function update(Category $Category): Category;
    public function delete(CategoryId $id): void;
}