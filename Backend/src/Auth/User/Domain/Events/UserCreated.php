<?php
namespace Src\Auth\User\Domain\Events;

use Src\Auth\User\Domain\Entities\User;

final class UserCreated
{
    public function __construct(public readonly User $User) {}
}