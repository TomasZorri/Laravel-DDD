<?php

namespace Src\Catalog\Product\Application\UseCases;

use Src\Catalog\Product\Application\Contracts\ProductReadRepositoryInterface;

final class GetAllProductsUseCase
{
    public function __construct(private ProductReadRepositoryInterface $repository)
    {
    }

    public function execute(array $filters = []): array
    {
        return $this->repository->findAll($filters);
    }
}
