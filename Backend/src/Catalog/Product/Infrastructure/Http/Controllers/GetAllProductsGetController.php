<?php

namespace Src\Catalog\Product\Infrastructure\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Src\Catalog\Product\Application\UseCases\GetAllProductsUseCase;
use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Mappers\ProductMapper;
use Src\Catalog\Product\Infrastructure\Http\Filters\ProductQueryFilter;

final class GetAllProductsGetController
{
    public function __construct(private GetAllProductsUseCase $useCase)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        # Transformar la respuesta 
        $filter = new ProductQueryFilter();
        $queryFilter = $filter->transform($request);


        # Pasamos los filtros en el caso de uso
        $products = $this->useCase->execute($queryFilter);

        return new JsonResponse(
            array_map(fn($product) => ProductMapper::toEloquent($product), $products),
            JsonResponse::HTTP_OK
        );
    }
}
