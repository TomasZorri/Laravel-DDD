<?php

namespace Src\Auth\User\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Auth\User\Domain\Contracts\UserRepositoryInterface;
use Src\Auth\User\Infrastructure\Persistence\Eloquent\Repositories\UserRepository;

final class PersistenceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }
}