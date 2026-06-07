<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('cliente')?->id;
        return [
            'nombre'              => 'required|string|max:100',
            'apellido'            => 'required|string|max:100',
            'email'               => "required|email|unique:clientes,email,{$id}",
            'telefono'            => 'nullable|string|max:20',
            'direccion'           => 'nullable|string|max:255',
            'ingresos_mensuales'  => 'nullable|numeric|min:0',
            'referencia_nombre'   => 'nullable|string|max:100',
            'referencia_telefono' => 'nullable|string|max:20',
        ];
    }
}