<?php

namespace Src\Catalog\Product\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Catalog\Product\Application\UseCases\GetProductUseCase;
use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Mappers\ProductMapper;

final class GetProductGetController
{
    public function __construct(private GetProductUseCase $useCase)
    {
    }

    public function __invoke(int $id): JsonResponse
    {
        $product = $this->useCase->execute($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            ProductMapper::toEloquent($product),
            JsonResponse::HTTP_OK
        );
    }
}
