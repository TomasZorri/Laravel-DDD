<?php

namespace Src\Catalog\Product\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Src\Catalog\Product\Domain\Events\ProductCreated;
use Src\Catalog\Product\Domain\Events\ProductDeleted;
use Src\Catalog\Product\Domain\Events\ProductUpdated;
use Src\Catalog\Product\Infrastructure\Cache\Listeners\ProductCacheInvalidator;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * El mapa de eventos y suscriptores de la aplicación.
     */
    protected $listen = [
        ProductCreated::class => [
            ProductCacheInvalidator::class,
        ],
        ProductUpdated::class => [
            ProductCacheInvalidator::class,
        ],
        ProductDeleted::class => [
            ProductCacheInvalidator::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
