<?php

namespace Src\Catalog\Category\Domain\Events;

use App\Shared\Domain\Bus\DomainEvent;
use DateTimeImmutable;
use Src\Catalog\Category\Domain\Entities\Category;

final class CategoryUpdated implements DomainEvent
{
    private const EVENT_NAME = 'catalog.category.updated';
    private string $occurredOn;

    public function __construct(
        private Category $category,
        ?string $occurredOn = null
    ) {
        $this->occurredOn = $occurredOn ?? (new DateTimeImmutable())->format('Y-m-d H:i:s');
    }

    public function eventName(): string
    {
        return self::EVENT_NAME;
    }

    public function aggregateId(): string
    {
        return $this->category->id();
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    public function toPrimitives(): array
    {
        return [
            'event_name' => $this->eventName(),
            'aggregate_id' => $this->aggregateId(),
            'occurred_on' => $this->occurredOn,
            'data' => [
                'category_id' => $this->aggregateId(),
                'nombre' => $this->category->nombre()->value(),
                'descripcion' => $this->category->descripcion()->value(),
                'estado' => $this->category->estado()->value(),
            ]
        ];
    }

    public static function fromPrimitives(array $data): self
    {
        // 1. Reconstruimos la entidad Category con los datos que vienen de RabbitMQ
        // Usamos el constructor de Category que ahora recibe strings crudos
        $category = new Category(
            (int) $data['data']['category_id'],
            $data['data']['nombre'],
            $data['data']['descripcion'],
            $data['data']['estado']
        );

        // 2. Ahora sí, llamamos al constructor con los 2 parámetros que espera
        return new self($category, $data['occurred_on']);
    }
}