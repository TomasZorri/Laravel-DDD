<?php

namespace App\Shared\Domain\Bus;

interface EventBus
{
    /**
     * Publica uno o más eventos de dominio.
     * 
     * @param DomainEvent ...$events
     */
    public function publish(DomainEvent ...$events): void;
}
