<?php

namespace App\Http\Requests;

use App\Models\Cliente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $cliente = $this->route('cliente');
        $clienteId = $cliente instanceof Cliente ? $cliente->getKey() : null;

        return [
            'nombre_completo' => ['required', 'string', 'max:150'],
            'empresa' => ['nullable', 'string', 'max:100'],
            'nit_ci' => ['nullable', 'string', 'max:30', Rule::unique('clientes', 'nit_ci')->ignore($clienteId, 'id_cliente')],
            'correo' => ['nullable', 'email', 'max:100', Rule::unique('clientes', 'correo')->ignore($clienteId, 'id_cliente')],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:65535'],
        ];
    }
}