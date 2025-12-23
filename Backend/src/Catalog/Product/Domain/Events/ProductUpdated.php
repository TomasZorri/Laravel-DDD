<?php

namespace Src\Catalog\Product\Domain\Events;

use App\Shared\Domain\Bus\DomainEvent;
use Src\Catalog\Product\Domain\Entities\Product;
use DateTimeImmutable;

final class ProductUpdated implements DomainEvent
{
    private const EVENT_NAME = 'catalog.product.updated';
    private string $occurredOn;

    public function __construct(
        private Product $product,
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
        return $this->product->id();
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
                'product_id' => $this->product->id(),
                'category_id' => $this->product->categoriaId()->value(),
                'nombre' => $this->product->nombre()->value(),
                'descripcion' => $this->product->descripcion()->value(),
                'precio' => $this->product->precio()->value(),
                'stock' => $this->product->stock()->value(),
                'sku' => $this->product->sku()->value(),
                'estado' => $this->product->estado()->value(),
            ]
        ];
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(Product::fromPrimitives($data['data']), $data['occurred_on']);
    }
}