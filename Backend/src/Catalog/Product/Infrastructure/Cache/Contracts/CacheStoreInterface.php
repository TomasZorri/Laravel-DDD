<?php
namespace Src\Catalog\Product\Infrastructure\Cache\Contracts;

interface CacheStoreInterface
{
    /**
     * Obtiene un valor desde cache
     */
    public function get(string $key): mixed;

    /**
     * Guarda un valor en cache por N segundos
     */
    public function put(string $key, mixed $value, int $ttl): void;

    /**
     * Obtiene o guarda el valor si no existe
     */
    public function remember(string $key, int $ttl, callable $callback): mixed;

    /**
     * Elimina una clave
     */
    public function forget(string $key): void;

    /**
     * Soporte para etiquetas de los metodos de filtrados
     */
    public function tags(array $names): self;
    public function flushTags(array $names): void;
}
