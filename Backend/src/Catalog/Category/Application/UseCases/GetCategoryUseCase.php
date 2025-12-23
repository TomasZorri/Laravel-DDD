<?php

namespace Src\Catalog\Category\Application\UseCases;

use Src\Catalog\Category\Domain\Contracts\CategoryRepositoryInterface;
use Src\Catalog\Category\Domain\Entities\Category;
use Src\Catalog\Category\Domain\ValueObjects\CategoryId;

final class GetCategoryUseCase
{
    public function __construct(private CategoryRepositoryInterface $repository)
    {
    }

    public function __invoke(int $id): ?Category
    {
        // 1. Convertimos el int a CategoryId (aquí se valida que sea > 0)
        $CategoryId = CategoryId::from($id);

        // 2. El repositorio ahora recibe el objeto CategoryId
        return $this->repository->findById($CategoryId);
    }
}
