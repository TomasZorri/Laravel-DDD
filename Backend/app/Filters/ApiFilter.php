<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class ApiFilter
{
    /**
     * Parámetros permitidos y sus operadores: ['columna' => ['eq', 'gt']]
     */
    protected array $safeParams = [];

    /**
     * Mapeo de nombres de la API a columnas de la BD: ['postalCode' => 'postal_code']
     */
    protected array $columnMap = [];

    /**
     * Mapeo de abreviaciones de la API a operadores SQL
     */
    protected array $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'like' => 'LIKE',
        'ne' => '!=',
    ];

    public function transform(Request $request): array
    {
        $eloQuery = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);

            // Si el parámetro no viene en la URL o no es un array, saltar
            if (!isset($query) || !is_array($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $value = $query[$operator];

                    // Lógica especial para búsquedas parciales
                    if ($operator === 'like') {
                        $value = "%{$value}%";
                    }

                    $eloQuery[] = [$column, $this->operatorMap[$operator], $value];
                }
            }
        }

        return $eloQuery;
    }
}