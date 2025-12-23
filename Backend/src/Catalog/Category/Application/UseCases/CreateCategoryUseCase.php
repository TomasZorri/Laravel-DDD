<?php

namespace Src\Catalog\Category\Application\UseCases;

use App\Shared\Domain\Bus\EventBus;

use Src\Catalog\Category\Application\Command\CreateCategoryCommand;
use Src\Catalog\Category\Domain\Contracts\CategoryRepositoryInterface;
use Src\Catalog\Category\Domain\Entities\Category;

final class CreateCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $repository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(CreateCategoryCommand $command): Category
    {
        // 1. Creamos la entidad
        $category = Category::create(
            $command->nombre,
            $command->descripcion,
            $command->estado
        );

        // 2. Persistir en DB
        $this->repository->save($category);

        // 3. Disparamos el evento de dominio
        $this->eventBus->publish(...$category->pullDomainEvents());

        return $category;
    }
}