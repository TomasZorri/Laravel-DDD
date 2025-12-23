<?php

namespace Src\Catalog\Product\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Catalog\Product\Application\UseCases\DeleteProductUseCase;
use Src\Catalog\Product\Domain\ValueObjects\ProductId;

final class DeleteProductDeleteController
{
    public function __construct(private DeleteProductUseCase $useCase)
    {
    }

    public function __invoke(int $id): JsonResponse
    {
        $this->useCase->execute(ProductId::from($id));

        return new JsonResponse(['message' => 'Producto eliminado correctamente'], JsonResponse::HTTP_OK);
    }
}
