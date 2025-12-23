<?php

namespace Src\Catalog\Category\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Src\Catalog\Category\Infrastructure\Http\Requests\CreateCategoryRequest;
use Src\Catalog\Category\Application\UseCases\CreateCategoryUseCase;
use Src\Catalog\Category\Application\Command\CreateCategoryCommand;

final class CreateCategoryPostController extends Controller
{

    public function __construct(private CreateCategoryUseCase $useCase)
    {
    }

    public function __invoke(CreateCategoryRequest $request)
    {
        //dd($request->all());
        $command = new CreateCategoryCommand(
            nombre: $request->nombre,
            descripcion: $request->descripcion,
            estado: $request->estado
        );

        $Category = ($this->useCase)($command);

        return response()->json(['id' => $Category->id()], 201);
    }
}