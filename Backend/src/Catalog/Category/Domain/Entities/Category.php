<?php

namespace Src\Catalog\Category\Domain\Entities;

use App\Shared\Domain\AggregateRoot;
use Src\Catalog\Category\Domain\ValueObjects\{CategoryName, CategoryDescription, CategoryState};
use Src\Catalog\Category\Domain\Events\{CategoryUpdated, CategoryCreated, CategoryDeleted};

final class Category extends AggregateRoot
{
    private CategoryName $nombre;
    private CategoryDescription $descripcion;
    private CategoryState $estado;

    public function __construct(
        private ?int $id,
        string $nombre,
        string $descripcion,
        string $estado
    ) {
        $this->id = $id;
        $this->nombre = new CategoryName($nombre);
        $this->descripcion = new CategoryDescription($descripcion);
        $this->estado = CategoryState::from($estado);
    }

    // Creaciones
    public static function create(
        string $nombre,
        string $descripcion,
        string $estado
    ): self {
        $category = new self(null, $nombre, $descripcion, $estado);

        // Registramos el evento dentro de la entidad
        $category->record(new CategoryCreated($category));

        return $category;
    }

    // ACTUALIZACIONES
    public function update(
        string $nombre,
        string $descripcion,
        string $estado
    ): void {
        // 1. Mutamos el estado interno usando Value Objects para validar
        $this->nombre = new CategoryName($nombre);
        $this->descripcion = new CategoryDescription($descripcion);
        $this->estado = CategoryState::from($estado);

        // 2. Registramos el evento pasándole la entidad completa
        // Al ser una actualización, el ID ya existe con seguridad
        $this->record(new CategoryUpdated($this));
    }

    // Eliminaciones
    public function delete(): void
    {
        $this->record(new CategoryUpdated($this));
    }


    // =======================
    // Getters
    // =======================

    public function id(): ?int
    {
        return $this->id;
    }

    public function nombre(): CategoryName
    {
        return $this->nombre;
    }
    public function descripcion(): CategoryDescription
    {
        return $this->descripcion;
    }

    public function estado(): CategoryState
    {
        return $this->estado;
    }
}