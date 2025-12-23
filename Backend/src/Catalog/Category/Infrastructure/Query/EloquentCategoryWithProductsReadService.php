<?php

namespace Src\Catalog\Category\Infrastructure\Query;

use Src\Catalog\Category\Application\Query\CategoryWithProductsReadServiceInterface;
use Src\Catalog\Category\Domain\Contracts\CategoryRepositoryInterface;
use Src\Catalog\Product\Application\Contracts\ProductReadRepositoryInterface;
use Src\Catalog\Category\Domain\ValueObjects\CategoryId;
use Src\Catalog\Category\Application\DTO\CategoryWithProductsResponse;

final class EloquentCategoryWithProductsReadService implements CategoryWithProductsReadServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private ProductReadRepositoryInterface $productRepository
    ) {
    }

    public function execute(
        int $categoryId,
        array $productFilters = []
    ): ?CategoryWithProductsResponse {
        $category = $this->categoryRepository
            ->findById(new CategoryId($categoryId));

        if ($category === null) {
            return null;
        }

        $products = $this->productRepository
            ->findByCategory($categoryId, $productFilters);

        return new CategoryWithProductsResponse(
            $category,
            $products
        );
    }
}
