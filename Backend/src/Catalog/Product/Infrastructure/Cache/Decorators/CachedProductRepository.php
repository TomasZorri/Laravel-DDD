<?php

namespace Src\Catalog\Product\Infrastructure\Cache\Decorators;

use Src\Catalog\Product\Domain\Contracts\ProductRepositoryInterface;
use Src\Catalog\Product\Infrastructure\Cache\Contracts\CacheStoreInterface;
use Src\Catalog\Product\Domain\Entities\Product;
use Src\Catalog\Product\Domain\ValueObjects\ProductId;

final class CachedProductRepository implements ProductRepositoryInterface
{
    private const TAG_LIST = ['products_list'];
    private const TTL = 3600;

    public function __construct(
        private ProductRepositoryInterface $repository,
        private CacheStoreInterface $cache
    ) {
    }

    public function findAll(array $filters = []): array
    {
        // Ordenamos los filtros para que el hash sea el mismo aunque cambie el orden en la URL
        ksort($filters);
        $cacheKey = 'Product.all.' . md5(json_encode($filters));

        return $this->cache->tags(self::TAG_LIST)->remember(
            $cacheKey,
            self::TTL,
            fn() => $this->repository->findAll($filters)
        );
    }

    public function findById(ProductId $id): ?Product
    {
        return $this->cache->remember(
            "Product.{$id->value()}",
            self::TTL,
            fn() => $this->repository->findById($id)
        );
    }

    public function save(Product $product): void
    {
        $this->repository->save($product);
        // Al guardar, invalidamos TODAS las listas filtradas
        $this->cache->flushTags(self::TAG_LIST);
    }

    public function update(Product $product): void
    {
        $this->repository->update($product);
        // Invalidamos listas y el producto específico
        $this->cache->flushTags(self::TAG_LIST);
        $this->cache->forget("Product.{$product->id()}");
    }

    public function delete(ProductId $id): void
    {
        $this->repository->delete($id);
        // Invalidamos listas y el producto específico
        $this->cache->flushTags(self::TAG_LIST);
        $this->cache->forget("Product.{$id->value()}");
    }
}