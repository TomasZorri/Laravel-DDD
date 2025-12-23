<?php

namespace Src\Catalog\Category\Application\UseCases;

use App\Shared\Domain\Bus\EventBus;

use Src\Catalog\Category\Domain\Contracts\CategoryRepositoryInterface;
use Src\Catalog\Category\Domain\ValueObjects\CategoryId;

final class DeleteCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $repository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(CategoryId $id): void
    {
        // 1. Verificar existencia
        $category = $this->repository->findById($id);

        if (!$category) {
            throw new \Exception('Category not found');
        }

        // 2. Ejecutar borrado en repositorio
        $this->repository->delete($id);

        // 3. Publicar evento de eliminación, con spread operator
        $this->eventBus->publish(...$category->pullDomainEvents());
    }
}
