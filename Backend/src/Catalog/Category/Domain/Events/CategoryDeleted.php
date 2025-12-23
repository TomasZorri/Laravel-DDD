<?php

namespace Src\Catalog\Category\Domain\Events;

use App\Shared\Domain\Bus\DomainEvent;
use Src\Catalog\Category\Domain\Entities\Category;
use DateTimeImmutable;

final class CategoryDeleted implements DomainEvent
{
    private const EVENT_NAME = 'catalog.category.deleted';
    private string $occurredOn;

    private function __construct(
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
            'event_name' => self::EVENT_NAME,
            'aggregate_id' => $this->aggregateId(),
            'occurred_on' => $this->occurredOn,
            'data' => [
                'category_id' => $this->category->id(),
            ],
        ];
    }

    public static function fromPrimitives(array $data): self
    {
        $category = new Category(
            (int) $data['data']['category_id'],
            'Categoría Eliminada',
            'Descripción de la categoría eliminada',
            'eliminado'
        );

        return new self($category, $data['occurred_on']);
    }
}
