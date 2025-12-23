<?php
namespace Src\Catalog\Product\Infrastructure\Cache\Redis;

use Illuminate\Support\Facades\Cache;
use Src\Catalog\Product\Infrastructure\Cache\Contracts\CacheStoreInterface;

final class RedisCacheStore implements CacheStoreInterface
{
    private $activeTags = [];

    public function tags(array $names): self
    {
        $this->activeTags = $names;
        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->getStore()->get($key);
    }

    public function put(string $key, mixed $value, int $ttl): void
    {
        $this->getStore()->put($key, $value, $ttl);
    }

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        return $this->getStore()->remember($key, $ttl, $callback);
    }

    public function forget(string $key): void
    {
        $this->getStore()->forget($key);
    }

    public function flushTags(array $names): void
    {
        $this->getStore()->tags($names)->flush();
    }

    /**
     * Centro de mando para obtener la instancia de caché
     * @return \Illuminate\Cache\TaggedCache|\Illuminate\Contracts\Cache\Repository
     */
    private function getStore()
    {
        /** @var \Illuminate\Cache\RedisStore|\Illuminate\Cache\TaggedCache $store */
        $store = Cache::store('redis');

        if (!empty($this->activeTags)) {
            $store = $store->tags($this->activeTags);
            $this->activeTags = [];
        }

        return $store;
    }
}