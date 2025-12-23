<?php

namespace Src\Catalog\Category\Application\UseCases;


use Src\Catalog\Category\Application\Query\CategoryWithProductsReadServiceInterface;
use Src\Catalog\Category\Application\DTO\CategoryWithProductsResponse;

final class GetCategoryWithProductsUseCase
{
    public function __construct(
        private CategoryWithProductsReadServiceInterface $readService
    ) {
    }

    public function __invoke(int $categoryId, array $filters = []): ?CategoryWithProductsResponse
    {
        return $this->readService->execute($categoryId, $filters);
    }
}



