<?php

namespace Src\Catalog\Category\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Catalog\Category\Application\UseCases\GetAllCategoryUseCase;
use Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Mappers\CategoryMapper;

final class GetAllCategoryGetController
{
    public function __construct(private GetAllCategoryUseCase $useCase)
    {
    }

    public function __invoke(): JsonResponse
    {
        $categorys = ($this->useCase)();

        return new JsonResponse(
            array_map(fn($Category) => CategoryMapper::toEloquent($Category), $categorys),
            JsonResponse::HTTP_OK
        );
    }
}
