<?php

namespace Src\Catalog\Category\Application\Listeners;

use Src\Catalog\Product\Domain\Events\ProductCreated;
use Src\Catalog\Category\Domain\Contracts\CategoryRepositoryInterface;
use Src\Catalog\Category\Domain\ValueObjects\CategoryId;
use Illuminate\Support\Facades\Log;

final class OnProductCreated
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function __invoke(ProductCreated $event): void
    {
        // ✅ Solo usa primitivos del evento
        $categoryId = new CategoryId((int) $event->categoryId());

        $category = $this->categoryRepository->findById($categoryId);

        if (null === $category) {
            Log::warning("Category not found: {$event->categoryId()}");
            return;
        }

        Log::info("Product '{$event->nombre()}' created in category '{$category->nombre()->value()}'");

        // Aquí podrías:
        // - Incrementar contador de productos
        // - Actualizar estadísticas
        // - Invalidar caché
    }
}
