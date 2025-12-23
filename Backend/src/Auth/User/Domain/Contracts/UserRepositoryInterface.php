<?php

namespace Src\Auth\User\Domain\Contracts;

use Src\Auth\User\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $course): void;
    public function findById(int $id): ?User;
    public function findAll(): array;
    public function delete(int $id): void;
}