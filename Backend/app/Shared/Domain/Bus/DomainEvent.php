<?php

namespace App\Shared\Domain\Bus;

interface DomainEvent
{
    /**
     * Nombre único del evento (ej: "catalog.product.created")
     */
    public function eventName(): string;

    /**
     * ID del agregado que generó el evento
     */
    public function aggregateId(): string;

    /**
     * Timestamp de cuándo ocurrió el evento
     */
    public function occurredOn(): string;

    /**
     * Convierte el evento a array de primitivos (para serialización)
     */
    public function toPrimitives(): array;

    /**
     * Reconstruye el evento desde primitivos (para deserialización)
     */
    public static function fromPrimitives(array $data): self;
}
