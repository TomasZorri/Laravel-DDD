<?php

namespace Src\Catalog\Category\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Catalog\Category\Application\UseCases\DeleteCategoryUseCase;
use Src\Catalog\Category\Domain\ValueObjects\CategoryId;

final class DeleteCategoryDeleteController
{
    public function __construct(private DeleteCategoryUseCase $useCase)
    {
    }

    public function __invoke(int $id): JsonResponse
    {
        ($this->useCase)(CategoryId::from($id));

        return new JsonResponse(['message' => 'Category eliminado correctamente'], JsonResponse::HTTP_OK);
    }
}
