<?php
namespace App\Http\Requests;

use App\Models\Prestamo;
use Illuminate\Foundation\Http\FormRequest;

class PagoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $prestamo = $this->route('prestamo');

        $cuotaActiva = $prestamo instanceof Prestamo
            ? $prestamo->cuotas()
                ->whereIn('estado', ['pendiente', 'parcialmente_pagada'])
                ->orderBy('numero')
                ->first()
            : null;

        $saldoPendiente = $cuotaActiva ? number_format($cuotaActiva->saldo_pendiente, 2, '.', '') : '0.00';

        return [
            'monto'      => "required|numeric|min:0.01|max:{$saldoPendiente}",
            'fecha_pago' => 'required|date|before_or_equal:today',
            'notas'      => 'nullable|string|max:500',
        ];
    }
}
