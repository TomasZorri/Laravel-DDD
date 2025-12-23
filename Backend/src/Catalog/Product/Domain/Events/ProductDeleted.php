<?php

namespace Src\Catalog\Product\Domain\Events;

use App\Shared\Domain\Bus\DomainEvent;
use Src\Catalog\Product\Domain\Entities\Product;
use DateTimeImmutable;

final class ProductDeleted implements DomainEvent
{
    private const EVENT_NAME = 'catalog.product.deleted';
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
            'event_name' => self::EVENT_NAME,
            'aggregate_id' => $this->aggregateId(),
            'occurred_on' => $this->occurredOn,
            'data' => [
                'product_id' => $this->product->id(),
            ],
        ];
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(Product::fromPrimitives($data['data']), $data['occurred_on']);
    }
}
