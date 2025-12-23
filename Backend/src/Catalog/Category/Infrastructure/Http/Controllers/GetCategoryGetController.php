<?php

namespace Src\Catalog\Category\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Catalog\Category\Application\UseCases\GetCategoryUseCase;
use Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Mappers\CategoryMapper;

final class GetCategoryGetController
{
    public function __construct(private GetCategoryUseCase $useCase)
    {
    }

    public function __invoke(int $id): JsonResponse
    {
        $category = ($this->useCase)($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            CategoryMapper::toEloquent($category),
            JsonResponse::HTTP_OK
        );
    }
}
