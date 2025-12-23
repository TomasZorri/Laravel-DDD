<?php

namespace Src\Auth\User\Infrastructure\Persistence\Eloquent\Mapper;

use Src\Auth\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Src\Auth\User\Domain\Entities\User;
use Src\Auth\User\Domain\ValueObjects\UserNombre;
use Src\Auth\User\Domain\ValueObjects\UserApellido;
use Src\Auth\User\Domain\ValueObjects\UserEmail;
use Src\Auth\User\Domain\ValueObjects\UserPassword;
use Src\Auth\User\Domain\ValueObjects\UserTelefono;
use Src\Auth\User\Domain\ValueObjects\UserEstado;

final class UserMapper
{
    public static function toEloquent(User $user): array
    {
        return [
            'id' => $user->id(),
            'nombre' => $user->nombre()->value(),
            'apellido' => $user->apellido()->value(),
            'email' => $user->email()->value(),
            'password' => $user->password()->value(),
            'telefono' => $user->telefono()->value(),
            'estado' => $user->estado()->value(),
        ];
    }

    public static function toDomain(UserModel $model): User
    {
        return new User(
            $model->id,
            new UserNombre($model->nombre),
            new UserApellido($model->apellido),
            new UserEmail($model->email),
            new UserPassword($model->password),
            new UserTelefono($model->telefono),
            new UserEstado($model->estado)
        );
    }
}