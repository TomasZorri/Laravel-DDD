<?php

namespace Src\Catalog\Category\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Catalog\Category\Application\UseCases\UpdateCategoryUseCase;
use Src\Catalog\Category\Application\Command\UpdateCategoryCommand;
use Src\Catalog\Category\Infrastructure\Http\Requests\UpdateCategoryRequest;

final class UpdateCategoryPutController
{
    public function __construct(private UpdateCategoryUseCase $useCase)
    {
    }

    public function __invoke(int $id, UpdateCategoryRequest $request): JsonResponse
    {

        // 1. Creamos el Command
        $command = new UpdateCategoryCommand(
            $id,
            nombre: $request->input('nombre'),
            descripcion: $request->input('descripcion'),
            estado: $request->input('estado')
        );

        // 2. Ejecutamos el caso de uso pasando solo el Command
        ($this->useCase)($command);

        return new JsonResponse(['message' => 'Category actualizado correctamente'], JsonResponse::HTTP_OK);
    }
}
