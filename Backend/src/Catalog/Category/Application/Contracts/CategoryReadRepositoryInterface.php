<?php

namespace Src\Catalog\Category\Application\Contracts;

use Src\Catalog\Category\Application\DTO\CategoryWithProductsResponse;
use Src\Catalog\Category\Domain\ValueObjects\CategoryId;
use Src\Catalog\Category\Domain\Entities\Category;


interface CategoryReadRepositoryInterface
{
    public function findBySlug(string $slug): array;
    public function findById(CategoryId $id): ?Category;
    public function findAll(array $filters = []): array;
}
