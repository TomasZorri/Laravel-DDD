<?php

namespace Src\Catalog\Category\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

final class CategoryModel extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
        'estado',
    ];
}