<?php

namespace Src\Catalog\Product\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Catalog\Product\Application\UseCases\UpdateProductUseCase;
use Src\Catalog\Product\Application\Command\UpdateProductCommand;
use Src\Catalog\Product\Infrastructure\Http\Requests\UpdateProductRequest;

final class UpdateProductPutController
{
    public function __construct(private UpdateProductUseCase $useCase)
    {
    }

    public function __invoke(int $id, UpdateProductRequest $request): JsonResponse
    {

        // 1. Creamos el Command
        $command = new UpdateProductCommand(
            $id,
            nombre: $request->input('nombre'),
            descripcion: $request->input('descripcion'),
            precio: $request->has('precio') ? (float) $request->input('precio') : null,
            stock: $request->has('stock') ? (int) $request->input('stock') : null,
            sku: $request->input('sku'),
            categoriaId: $request->has('categoria_id') ? (int) $request->input('categoria_id') : null,
            estado: $request->input('estado')
        );

        // 2. Ejecutamos el caso de uso pasando solo el Command
        $this->useCase->execute($command);

        return new JsonResponse(['message' => 'Producto actualizado correctamente'], JsonResponse::HTTP_OK);
    }
}
