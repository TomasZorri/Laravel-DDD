<?php

namespace Src\Catalog\Product\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Catalog\Product\Domain\Contracts\ProductRepositoryInterface;
use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Repositories\ProductRepository;

use Src\Catalog\Product\Application\Contracts\ProductReadRepositoryInterface;
use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Repositories\ProductRedRepository;


final class PersistenceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // CRUD Repositorio
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );


        // Read Repositorio
        $this->app->bind(
            ProductReadRepositoryInterface::class,
            ProductRedRepository::class
        );
    }
}