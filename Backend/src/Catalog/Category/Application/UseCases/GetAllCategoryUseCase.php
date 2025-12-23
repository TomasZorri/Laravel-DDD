<?php

namespace Src\Catalog\Category\Application\UseCases;

use Src\Catalog\Category\Domain\Contracts\CategoryRepositoryInterface;

final class GetAllCategoryUseCase
{
    public function __construct(private CategoryRepositoryInterface $repository)
    {
    }

    public function __invoke(array $filters = []): array
    {
        return $this->repository->findAll($filters);
    }
}
