<?php

namespace Src\Catalog\Product\Application\UseCases;

use App\Shared\Domain\Bus\EventBus;
use Src\Catalog\Product\Application\Command\UpdateProductCommand;
use Src\Catalog\Product\Domain\Contracts\ProductRepositoryInterface;

use Src\Catalog\Product\Domain\ValueObjects\ProductId;

final class UpdateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private EventBus $eventBus
    ) {
    }

    public function execute(UpdateProductCommand $command): void
    {
        // 1. Buscamos el producto, con el value object (El repositorio debería aceptar el VO)
        $product = $this->repository->findById(ProductId::from($command->id));

        if (!$product) {
            throw new \Exception('Product not found');
        }

        // 2. Actualizamos la entidad
        $product->update(
            $command->nombre ?? $product->nombre(),
            $command->descripcion ?? $product->descripcion(),
            $command->precio ?? $product->precio(),
            $command->stock ?? $product->stock(),
            $command->sku ?? $product->sku(),
            $command->categoriaId ?? $product->categoriaId(),
            $command->estado ?? $product->estado(),
        );

        // 3. Persistir en DB
        $this->repository->update($product);

        // 4. Publicar eventos de dominio con spread operator
        $this->eventBus->publish(...$product->pullDomainEvents());
    }
}
