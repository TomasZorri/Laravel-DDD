<?php

namespace Src\Catalog\Product\Infrastructure\Http\Filters;

use App\Filters\ApiFilter;

final class ProductQueryFilter extends ApiFilter
{
    protected array $safeParams = [
        'nombre' => ['eq', 'like'],
        'precio' => ['eq', 'gt', 'lt', 'gte', 'lte'],
        'stock' => ['eq', 'gt', 'lt'],
        'sku' => ['eq'],
    ];

    protected array $columnMap = [];
}