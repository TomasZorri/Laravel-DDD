<?php

namespace Src\Catalog\Product\Application\UseCases;

use App\Shared\Domain\Bus\EventBus;
use Src\Catalog\Product\Application\Command\CreateProductCommand;

use Src\Catalog\Product\Domain\Contracts\ProductRepositoryInterface;
use Src\Catalog\Product\Domain\Entities\Product;

final class CreateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private EventBus $eventBus
    ) {
    }

    public function execute(CreateProductCommand $command): Product
    {
        $product = Product::create(
            $command->nombre,
            $command->descripcion,
            $command->precio,
            $command->stock,
            $command->sku,
            $command->categoriaId,
            $command->estado
        );

        $this->repository->save($product);

        // Publicar eventos de dominio con spread operator
        $this->eventBus->publish(...$product->pullDomainEvents());


        return $product;
    }
}