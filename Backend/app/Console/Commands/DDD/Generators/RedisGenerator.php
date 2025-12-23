<?php

namespace App\Console\Commands\Generators;

class RedisGenerator extends BaseGenerator
{
    public function generate(string $basePath, string $context, string $module): void
    {
        $replacements = ['context' => $context, 'module' => $module];
        $files = [
            "Infrastructure/Cache/Redis/RedisCacheStore.php" => "Infrastructure/Cache/Redis/RedisCacheStore",
            "Infrastructure/Cache/Decorators/Cached{$module}Repository.php" => "Infrastructure/Cache/Decorators/CachedProductRepository",
            "Infrastructure/Cache/Listeners/{$module}CacheInvalidator.php" => "Infrastructure/Cache/Listeners/ProductCacheInvalidator",
            "Infrastructure/Cache/Contracts/CacheStoreInterface.php" => "Infrastructure/Cache/Contracts/CacheStoreInterface",


            # Providers
            "Infrastructure/Providers/EventServiceProvider.php" => "Infrastructure/Providers/EventServiceProvider",
            "Infrastructure/Providers/RepositoryServiceProvider.php" => "Infrastructure/Providers/RepositoryServiceProvider",
        ];
        $this->generateBatch($basePath, $files, $replacements);
    }
}