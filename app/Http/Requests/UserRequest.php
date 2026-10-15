<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-users') ?? false;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        $userId = $user instanceof User ? $user->getKey() : null;

        return [
            'nombre' => ['required', 'string', 'max:100'],
            'correo' => ['required', 'email', 'max:100', Rule::unique('users', 'correo')->ignore($userId, 'id_usuario')],
            'password' => [$this->isMethod('POST') ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', Rule::in(['Administrador', 'Vendedor', 'Cajero'])],
            'estado' => ['required', 'boolean'],
        ];
    }
}