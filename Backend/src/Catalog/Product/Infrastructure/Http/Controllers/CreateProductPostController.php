<?php

namespace Src\Catalog\Product\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Src\Catalog\Product\Application\UseCases\CreateProductUseCase;
use Src\Catalog\Product\Application\Command\CreateProductCommand;
use Src\Catalog\Product\Infrastructure\Http\Requests\CreateProductRequest;

final class CreateProductPostController extends Controller
{

    public function __construct(private CreateProductUseCase $useCase)
    {
    }

    public function __invoke(CreateProductRequest $request)
    {
        //dd($request->all());
        $command = new CreateProductCommand(
            nombre: $request->nombre,
            descripcion: $request->descripcion,
            precio: $request->precio,
            stock: $request->stock,
            sku: $request->sku,
            categoriaId: $request->categoria_id,
            estado: $request->estado
        );

        $product = $this->useCase->execute($command);

        return response()->json(['id' => $product->id()], 201);
    }
}