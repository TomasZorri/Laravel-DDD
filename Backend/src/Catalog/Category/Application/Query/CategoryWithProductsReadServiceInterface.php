<?php

namespace Src\Catalog\Category\Application\Query;

use Src\Catalog\Category\Application\DTO\CategoryWithProductsResponse;

interface CategoryWithProductsReadServiceInterface
{
    public function execute(
        int $categoryId,
        array $productFilters = []
    ): ?CategoryWithProductsResponse;
}
