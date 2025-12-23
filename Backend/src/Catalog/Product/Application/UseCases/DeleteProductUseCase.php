<?php

namespace Src\Catalog\Product\Application\UseCases;

use App\Shared\Domain\Bus\EventBus;

use Src\Catalog\Product\Domain\Contracts\ProductRepositoryInterface;
use Src\Catalog\Product\Domain\ValueObjects\ProductId;

final class DeleteProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private EventBus $eventBus
    ) {
    }

    public function execute(ProductId $id): void
    {
        // 1. Verificar existencia
        $product = $this->repository->findById($id);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        // 2. Ejecutar borrado en repositorio
        $this->repository->delete($id);

        // 3. Publicar evento de eliminación, con spread operator
        $this->eventBus->publish(...$product->pullDomainEvents());
    }
}
