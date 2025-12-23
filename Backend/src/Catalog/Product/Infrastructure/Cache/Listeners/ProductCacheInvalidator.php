<?php

namespace Src\Catalog\Product\Infrastructure\Cache\Listeners;

use Src\Catalog\Product\Domain\Events\ProductCreated;
use Src\Catalog\Product\Domain\Events\ProductUpdated;
use Src\Catalog\Product\Domain\Events\ProductDeleted;
use Src\Catalog\Product\Infrastructure\Cache\Contracts\CacheStoreInterface;
use Illuminate\Contracts\Queue\ShouldQueue; // Verificar si es necesario, por si anda lento por la conección.

final class ProductCacheInvalidator implements ShouldQueue
{
    public function __construct(
        private CacheStoreInterface $cache
    ) {
    }

    public function handle(ProductCreated|ProductUpdated|ProductDeleted $event): void
    {
        // Borramos todas las listas filtradas
        $this->cache->flushTags(['products_list']);

        // 🔹 ID uniforme desde el contrato
        $productId = $event->aggregateId();
        $this->cache->forget("Product.{$productId}");
    }
}