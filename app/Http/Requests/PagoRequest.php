<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'monto'      => 'required|numeric|min:0.01',
            'fecha_pago' => 'required|date|before_or_equal:today',
            'notas'      => 'nullable|string|max:500',
        ];
    }
}