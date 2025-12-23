<?php

namespace Src\Catalog\Product\Application\UseCases;

use Src\Catalog\Product\Domain\Contracts\ProductRepositoryInterface;
use Src\Catalog\Product\Domain\Entities\Product;
use Src\Catalog\Product\Domain\ValueObjects\ProductId;

final class GetProductUseCase
{
    public function __construct(private ProductRepositoryInterface $repository)
    {
    }

    public function execute(int $id): ?Product
    {
        // 1. Convertimos el int a ProductId (aquí se valida que sea > 0)
        $productId = ProductId::from($id);

        // 2. El repositorio ahora recibe el objeto ProductId
        return $this->repository->findById($productId);
    }
}
