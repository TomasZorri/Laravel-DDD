<?php

namespace Src\Auth\User\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

final class UserModel extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'id',
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'estado',
    ];
}