<?php

namespace Src\Catalog\Category\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Catalog\Category\Domain\Contracts\CategoryRepositoryInterface;
use Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Repositories\CategoryRepository;
use Src\Catalog\Category\Application\Query\CategoryWithProductsReadServiceInterface;
use Src\Catalog\Category\Infrastructure\Query\EloquentCategoryWithProductsReadService;

final class PersistenceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding del Repositorio
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );



        // Conectando el servicio de lectura
        $this->app->bind(
            CategoryWithProductsReadServiceInterface::class,
            EloquentCategoryWithProductsReadService::class
        );
    }
}