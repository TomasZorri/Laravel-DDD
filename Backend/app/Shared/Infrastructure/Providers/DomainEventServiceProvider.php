<?php

namespace App\Shared\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Src\Catalog\Product\Domain\Events\ProductCreated;
use Src\Catalog\Product\Domain\Events\ProductUpdated;
use Src\Catalog\Category\Domain\Events\CategoryUpdated;

// Listeners
use Src\Catalog\Category\Application\Listeners\OnProductCreated as CategoryOnProductCreated;
use Src\Order\Application\Listeners\OnProductCreated as OrderOnProductCreated;

final class DomainEventServiceProvider extends ServiceProvider
{
    protected $listen = [
            // ✅ Múltiples módulos escuchan el mismo evento
        ProductCreated::class => [
            CategoryOnProductCreated::class,
            OrderOnProductCreated::class,
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
