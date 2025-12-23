<?php

namespace Src\Catalog\Product\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use Src\Catalog\Product\Domain\Contracts\ProductRepositoryInterface;

use Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Repositories\ProductRepository;
use Src\Catalog\Product\Infrastructure\Cache\Decorators\CachedProductRepository;
use Src\Catalog\Product\Infrastructure\Cache\Contracts\CacheStoreInterface;
use Src\Catalog\Product\Infrastructure\Cache\Redis\RedisCacheStore;



final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        // 1. Registramos la implementación de la caché como singleton
        // (Singleton es mejor para caché para reutilizar la conexión a Redis)
        $this->app->singleton(CacheStoreInterface::class, RedisCacheStore::class);

        // 2. Registramos el Repositorio Decorado
        $this->app->bind(ProductRepositoryInterface::class, function ($app) {

            // Usamos $app->make para que Laravel construya el repo real
            $eloquentRepository = $app->make(ProductRepository::class);

            // Retornamos el decorador
            return new CachedProductRepository(
                $eloquentRepository,
                $app->make(CacheStoreInterface::class)
            );
        });
    }
}