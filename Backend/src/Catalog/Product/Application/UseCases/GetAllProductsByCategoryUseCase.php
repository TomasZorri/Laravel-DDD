<?php

namespace Src\Catalog\Product\Application\UseCases;

use Src\Catalog\Product\Application\Contracts\ProductReadRepositoryInterface;

final class GetAllProductsByCategoryUseCase
{
    public function __construct(private ProductReadRepositoryInterface $repository)
    {
    }

    public function execute(int $categoryId, array $filters = []): array
    {
        return $this->repository->findByCategory($categoryId, $filters);
    }
}
