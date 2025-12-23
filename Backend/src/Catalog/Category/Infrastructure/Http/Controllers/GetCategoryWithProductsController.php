<?php

namespace Src\Catalog\Category\Infrastructure\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Src\Catalog\Category\Application\UseCases\GetCategoryWithProductsUseCase;
use Src\Catalog\Category\Infrastructure\Http\Filters\CategoryProductQueryFilter;

final class GetCategoryWithProductsController
{
    public function __construct(
        private GetCategoryWithProductsUseCase $useCase
    ) {
    }

    public function __invoke(string $slug, Request $request): JsonResponse
    {
        $filter = new CategoryProductQueryFilter();
        $productFilters = $filter->transform($request);

        $response = ($this->useCase)($slug, $productFilters);

        if ($response === null) {
            return new JsonResponse(
                ['error' => 'Category not found'],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            $response->toArray(),
            JsonResponse::HTTP_OK
        );
    }
}
