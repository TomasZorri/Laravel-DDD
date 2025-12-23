<?php

namespace Src\Catalog\Product\Domain\Contracts;

use Src\Catalog\Product\Domain\Entities\Product;
use Src\Catalog\Product\Domain\ValueObjects\ProductId;

interface ProductRepositoryInterface
{
    public function save(Product $product): Product;
    public function findById(ProductId $id): ?Product;
    public function findAll(array $filters = []): array;
    public function update(Product $product): Product;
    public function delete(ProductId $id): void;
}