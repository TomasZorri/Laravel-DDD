<?php

namespace Src\Catalog\Product\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|min:3',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:productos,sku',
            'categoria_id' => 'required|exists:categorias,id',
            'estado' => 'required|in:activo,inactivo',
        ];
    }
}