<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrestamoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'cliente_id'   => 'required|exists:clientes,id',
            'capital'      => 'required|numeric|min:100',
            'tasa_anual'   => 'required|numeric|min:0.01|max:100',
            'plazo_meses'  => 'required|integer|min:1|max:360',
            'frecuencia'   => 'required|in:mensual',
            'fecha_inicio' => 'required|date|after_or_equal:today',
        ];
    }
}