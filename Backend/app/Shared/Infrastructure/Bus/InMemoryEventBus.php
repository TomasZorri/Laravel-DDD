<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Bus\EventBus;
use App\Shared\Domain\Bus\DomainEvent;
use Illuminate\Support\Facades\Event;

final class InMemoryEventBus implements EventBus
{
    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            // Laravel Events solo como transporte
            Event::dispatch($event);
        }
    }
}
