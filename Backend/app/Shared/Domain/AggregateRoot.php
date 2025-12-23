<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Bus\DomainEvent;

abstract class AggregateRoot
{
    private array $domainEvents = [];

    /**
     * Registra un evento de dominio
     */
    protected function record(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * Obtiene y limpia los eventos registrados
     */
    public function pullDomainEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }
}
