<?php

namespace Src\Auth\User\Domain\Entities;

use Src\Auth\User\Domain\ValueObjects\UserNombre;
use Src\Auth\User\Domain\ValueObjects\UserApellido;
use Src\Auth\User\Domain\ValueObjects\UserEmail;
use Src\Auth\User\Domain\ValueObjects\UserPassword;
use Src\Auth\User\Domain\ValueObjects\UserTelefono;
use Src\Auth\User\Domain\ValueObjects\UserEstado;

final class User
{
    public function __construct(
        private ?int $id,
        private UserNombre $nombre,
        private UserApellido $apellido,
        private UserEmail $email,
        private UserPassword $password,
        private UserTelefono $telefono,
        private UserEstado $estado
    ) {
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function nombre(): UserNombre
    {
        return $this->nombre;
    }
    public function apellido(): UserApellido
    {
        return $this->apellido;
    }
    public function email(): UserEmail
    {
        return $this->email;
    }
    public function password(): UserPassword
    {
        return $this->password;
    }
    public function telefono(): UserTelefono
    {
        return $this->telefono;
    }
    public function estado(): UserEstado
    {
        return $this->estado;
    }
}