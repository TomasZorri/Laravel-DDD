<?php

namespace Src\Catalog\Product\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

final class ProductModel extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'sku',
        'categoria_id',
        'estado',
    ];
}