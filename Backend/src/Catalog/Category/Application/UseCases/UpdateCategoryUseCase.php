<?php

namespace Src\Catalog\Category\Application\UseCases;

use App\Shared\Domain\Bus\EventBus;
use Src\Catalog\Category\Application\Command\UpdateCategoryCommand;
use Src\Catalog\Category\Domain\Contracts\CategoryRepositoryInterface;

use Src\Catalog\Category\Domain\ValueObjects\CategoryId;

final class UpdateCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $repository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(UpdateCategoryCommand $command): void
    {
        // 1. Buscamos el Category, con el value object (El repositorio debería aceptar el VO)
        $category = $this->repository->findById(CategoryId::from($command->id));

        if (!$category) {
            throw new \Exception('Category not found');
        }

        // 2. Actualizamos la entidad
        $category->update(
            $command->nombre ?? $category->nombre()->value(),
            $command->descripcion ?? $category->descripcion()->value(),
            $command->estado ?? $category->estado()->value(),
        );

        // 3. Persistir en DB
        $this->repository->update($category);

        // 4. DESPACHAR: Sacamos los eventos de la entidad y los publicamos
        $this->eventBus->publish(...$category->pullDomainEvents());
    }
}
