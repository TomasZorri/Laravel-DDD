<?php

namespace Src\Auth\User\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Src\Auth\User\Application\CreateUserUseCase;
use Src\Auth\User\Infrastructure\Http\Requests\CreateUserRequest;

final class CreateUserPostController extends Controller
{
    public function __invoke(CreateUserRequest $request, CreateUserUseCase $useCase)
    {

        $User = $useCase->execute(
            id: $request->id,
            name: $request->name,
            description: $request->description,
            price: $request->price
        );

        return response()->json(['id' => $User->id()], 201);
    }
}