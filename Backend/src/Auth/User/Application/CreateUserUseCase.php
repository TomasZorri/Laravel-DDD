<?php

namespace Src\Auth\User\Application;

use Src\Auth\User\Domain\Contracts\UserRepositoryInterface;
use Src\Auth\User\Domain\Entities\User;
use Src\Auth\User\Domain\Events\UserCreated;
use Src\Auth\User\Domain\ValueObjects\UserName;
use Src\Auth\User\Domain\ValueObjects\UserDescription;
use Src\Auth\User\Domain\ValueObjects\UserPrice;

final class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private ?DomainEventDispatcher $dispatcher = null
    ) {}

    public function execute(int $id, string $name, string $description, float $price): User
    {
        $User = new User(
            $id,
            new UserName($name),
            new UserDescription($description),
            new UserPrice($price)
        );

        $this->repository->save($User);

        // Disparamos el evento de dominio
        if ($this->dispatcher) {
            $this->dispatcher->dispatch(new UserCreated($User));
        }

        return $User;
    }
}