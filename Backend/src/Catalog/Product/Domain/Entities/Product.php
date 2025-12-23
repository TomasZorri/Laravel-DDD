<?php

namespace Src\Catalog\Product\Domain\Entities;

use App\Shared\Domain\AggregateRoot;
use Src\Catalog\Product\Domain\ValueObjects\{
    ProductNombre,
    ProductDescripcion,
    ProductPrecio,
    ProductStock,
    ProductSku,
    ProductCategoriaId,
    ProductEstado
};
use Src\Catalog\Product\Domain\Events\{
    ProductUpdated,
    ProductDeleted,
    ProductCreated
};

final class Product extends AggregateRoot
{
    private ProductNombre $nombre;
    private ProductDescripcion $descripcion;
    private ProductPrecio $precio;
    private ProductStock $stock;
    private ProductSku $sku;
    private ProductCategoriaId $categoriaId;
    private ProductEstado $estado;


    public function __construct(
        private ?int $id,


        string $nombre,
        string $descripcion,
        float $precio,
        int $stock,
        string $sku,
        int $categoriaId,
        string $estado
    ) {
        $this->id = $id;
        $this->nombre = new ProductNombre($nombre);
        $this->descripcion = new ProductDescripcion($descripcion);
        $this->precio = new ProductPrecio($precio);
        $this->stock = new ProductStock($stock);
        $this->sku = new ProductSku($sku);
        $this->categoriaId = new ProductCategoriaId($categoriaId);
        $this->estado = ProductEstado::from($estado);
    }

    // Creaciones
    public static function create(
        string $nombre,
        string $descripcion,
        float $precio,
        int $stock,
        string $sku,
        int $categoriaId,
        string $estado
    ): self {
        $product = new self(
            null,
            $nombre,
            $descripcion,
            $precio,
            $stock,
            $sku,
            $categoriaId,
            $estado
        );

        // Registramos el evento con primitivos
        $product->record(new ProductCreated($product));

        return $product;
    }

    // ACTUALIZACIONES
    public function update(
        string $nombre,
        string $descripcion,
        float $precio,
        int $stock,
        string $sku,
        int $categoriaId,
        string $estado
    ): void {
        $this->nombre = new ProductNombre($nombre);
        $this->descripcion = new ProductDescripcion($descripcion);
        $this->precio = new ProductPrecio($precio);
        $this->stock = new ProductStock($stock);
        $this->sku = new ProductSku($sku);
        $this->categoriaId = new ProductCategoriaId($categoriaId);
        $this->estado = ProductEstado::from($estado);

        // Registramos el evento con primitivos
        $this->record(new ProductUpdated($this));
    }


    // Eliminaciones
    public function delete(): void
    {
        $this->record(new ProductDeleted($this));
    }


    // Para datos de eventos
    public static function fromPrimitives(array $data): self
    {
        return new self(
            isset($data['category_id']) ? (int) $data['category_id'] : null,
            $data['nombre'] ?? '',
            $data['descripcion'] ?? '',
            $data['precio'] ?? 0.0,
            $data['stock'] ?? 0,
            $data['sku'] ?? '',
            $data['categoria_id'] ?? 0,
            $data['estado'] ?? 'activo'
        );
    }


    // =======================
    // Getters
    // =======================

    public function id(): ?int
    {
        return $this->id;
    }

    public function nombre(): ProductNombre
    {
        return $this->nombre;
    }
    public function descripcion(): ProductDescripcion
    {
        return $this->descripcion;
    }
    public function precio(): ProductPrecio
    {
        return $this->precio;
    }
    public function stock(): ProductStock
    {
        return $this->stock;
    }
    public function sku(): ProductSku
    {
        return $this->sku;
    }
    public function categoriaId(): ProductCategoriaId
    {
        return $this->categoriaId;
    }
    public function estado(): ProductEstado
    {
        return $this->estado;
    }
}