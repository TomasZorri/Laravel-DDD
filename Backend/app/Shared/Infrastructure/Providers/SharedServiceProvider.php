<?php

namespace App\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Shared\Domain\Bus\EventBus;
use App\Shared\Infrastructure\Bus\InMemoryEventBus;

final class SharedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind EventBus interface to InMemoryEventBus implementation
        $this->app->bind(EventBus::class, InMemoryEventBus::class);
    }

    public function boot(): void
    {
        //
    }
}
