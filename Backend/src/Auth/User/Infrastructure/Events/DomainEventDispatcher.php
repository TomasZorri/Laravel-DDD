<?php

namespace Src\Auth\User\Infrastructure\Events;

final class DomainEventDispatcher
{
    private array $listeners = [];

    public function register(string $eventClass, callable $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    public function dispatch(object $event): void
    {
        $eventClass = get_class($event);
        foreach ($this->listeners[$eventClass] ?? [] as $listener) {
            $listener($event);
        }
    }
}