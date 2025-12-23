<?php

namespace Src\Catalog\Product\Application\Contracts;

interface ProductReadRepositoryInterface
{

    public function findAll(array $filters = []): array;
    public function findByCategory(int $categoryId, array $filters = []): array;
}
