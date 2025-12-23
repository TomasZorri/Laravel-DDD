<?php

namespace Src\Auth\User\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Auth\User\Domain\Contracts\UserRepositoryInterface;
use Src\Auth\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Src\Auth\User\Infrastructure\Persistence\Eloquent\Mapper\UserMapper;
use Src\Auth\User\Domain\Entities\User;

final class UserRepository implements UserRepositoryInterface
{
    public function save(User $User): void
    {
        $model = UserModel::updateOrCreate(
            ['id' => $User->id()],
            UserMapper::toEloquent($User)
        );

        if ($User->id() === null) {
            $User->setId($model->id);
        }
    }

    public function findById(int $id): ?User
    {
        $model = UserModel::find($id);

        return $model
            ? UserMapper::toDomain($model)
            : null;
    }

    public function findAll(): array {}
    public function delete(int $id): void {}

}