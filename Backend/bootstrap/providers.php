<?php

return [
    // Shared Kernel Providers
    App\Shared\Infrastructure\Providers\SharedServiceProvider::class,

    // Module Providers
    Src\Auth\User\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Auth\Client\Infrastructure\Providers\PersistenceServiceProvider::class,

    Src\Catalog\Product\Infrastructure\Providers\RepositoryServiceProvider::class,
    Src\Catalog\Product\Infrastructure\Providers\EventServiceProvider::class,
    Src\Catalog\Product\Infrastructure\Providers\PersistenceServiceProvider::class,

    Src\Catalog\Category\Infrastructure\Providers\EventServiceProvider::class,
    Src\Catalog\Category\Infrastructure\Providers\PersistenceServiceProvider::class,

    Src\Catalog\ProductImage\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Customer\Profile\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Customer\Address\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Cart\Cart\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Cart\Cart\Infrastructure\Providers\EventServiceProvider::class,
    Src\Cart\Cart\Infrastructure\Providers\CacheServiceProvider::class,
    Src\Cart\Cart\Infrastructure\Providers\CacheDecoratorProvider::class,
    Src\Cart\CartItem\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Order\Order\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Order\Order\Infrastructure\Providers\EventServiceProvider::class,
    Src\Order\Order\Infrastructure\Providers\MessagingServiceProvider::class,
    Src\Order\OrderItem\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Payment\Payment\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Payment\Payment\Infrastructure\Providers\EventServiceProvider::class,
    Src\Payment\Payment\Infrastructure\Providers\MessagingServiceProvider::class,
    Src\Shipping\Shipment\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\Review\ProductReview\Infrastructure\Providers\PersistenceServiceProvider::class,
    Src\AdminLog\Log\Infrastructure\Providers\PersistenceServiceProvider::class,
];