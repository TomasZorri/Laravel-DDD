<?php

namespace Src\Category\Category\Infrastructure\Http\Filters;

use App\Filters\ApiFilter;

final class CategoryQueryFilter extends ApiFilter
{
    protected array $safeParams = [
        'nombre' => ['eq', 'like'],
    ];

    protected array $columnMap = [];
}