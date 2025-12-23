<?php

namespace Src\Auth\User\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
        ];
    }
}