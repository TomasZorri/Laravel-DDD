<?php

namespace Src\Catalog\Category\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// Eventos Internos
use Src\Catalog\Category\Domain\Events\CategoryUpdated;

// Eventos Externos
use Src\Catalog\Product\Domain\Events\ProductCreated;
use Src\Catalog\Product\Domain\Events\ProductUpdated;

// Listeners
use Src\Catalog\Category\Application\Listeners\OnProductCreated as CategoryOnProductCreated;

final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProductCreated::class => [
            CategoryOnProductCreated::class,
        ],

        ProductUpdated::class => [
            // Listeners para ProductUpdated si los necesitas
        ],

        CategoryUpdated::class => [
            // Listeners para CategoryUpdated si los necesitas
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
