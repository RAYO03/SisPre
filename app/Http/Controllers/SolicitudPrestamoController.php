<?php

namespace App\Http\Controllers;

use App\Models\SolicitudPrestamo;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SolicitudPrestamoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORMULARIO USUARIO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('usuario.prestamos.create');
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR SOLICITUDES ADMIN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $solicitudes = SolicitudPrestamo::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    /*
    |--------------------------------------------------------------------------
    | VER SOLICITUD
    |--------------------------------------------------------------------------
    */

    public function show(SolicitudPrestamo $solicitud)
    {
        $solicitud->load('user');

        return view('admin.solicitudes.show', compact('solicitud'));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR SOLICITUD
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $data = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Datos personales
            |--------------------------------------------------------------------------
            */

            'telefono' => 'required|string|max:20',
            'curp' => 'nullable|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'fecha_nacimiento' => 'nullable|date',

            'direccion' => 'required|string',

            'ciudad' => 'required|string|max:255',
            'estado_residencia' => 'required|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | Información laboral
            |--------------------------------------------------------------------------
            */

            'empresa' => 'nullable|string|max:255',
            'puesto' => 'nullable|string|max:255',
            'antiguedad_laboral' => 'nullable|string|max:255',

            'ingreso_mensual' => 'required|numeric|min:0',

            'tipo_empleo' => 'nullable|string|max:255',
            'telefono_trabajo' => 'nullable|string|max:20',

            /*
            |--------------------------------------------------------------------------
            | Información préstamo
            |--------------------------------------------------------------------------
            */

            'monto_solicitado' => 'required|numeric|min:1',

            'plazo_meses' => 'required|integer|min:1',

            'frecuencia_pago' => 'required|in:semanal,quincenal,mensual',

            'motivo' => 'nullable|string',

            /*
            |--------------------------------------------------------------------------
            | Referencias
            |--------------------------------------------------------------------------
            */

            'referencia1_nombre' => 'nullable|string|max:255',
            'referencia1_telefono' => 'nullable|string|max:20',
            'referencia1_relacion' => 'nullable|string|max:255',

            'referencia2_nombre' => 'nullable|string|max:255',
            'referencia2_telefono' => 'nullable|string|max:20',
            'referencia2_relacion' => 'nullable|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | Confirmaciones
            |--------------------------------------------------------------------------
            */

            'acepta_terminos' => 'required',
            'autoriza_validacion' => 'required',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Usuario autenticado
        |--------------------------------------------------------------------------
        */

        $data['user_id'] = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Estado inicial
        |--------------------------------------------------------------------------
        */

        $data['estado'] = 'pendiente';

        /*
        |--------------------------------------------------------------------------
        | Guardar solicitud
        |--------------------------------------------------------------------------
        */

        SolicitudPrestamo::create($data);

        return redirect()
            ->route('usuario.prestamos.create')
            ->with(
                'success',
                'Solicitud enviada correctamente. Espera la revisión del administrador.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | APROBAR SOLICITUD
    |--------------------------------------------------------------------------
    */

    public function aprobar(SolicitudPrestamo $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {

            return back()->with(
                'error',
                'Esta solicitud ya fue revisada.'
            );
        }

        $capital = $solicitud->monto_solicitado;

        $plazo = $solicitud->plazo_meses;

        $tasaAnual = 10;

        $montoCuota = $capital / $plazo;

        /*
        |--------------------------------------------------------------------------
        | Crear préstamo
        |--------------------------------------------------------------------------
        */

        $prestamo = Prestamo::create([

            'user_id' => $solicitud->user_id,

            'cliente_id' => $solicitud->user_id,

            'capital' => $capital,

            'tasa_anual' => $tasaAnual,

            'plazo_meses' => $plazo,

            'frecuencia' => $solicitud->frecuencia_pago,

            'fecha_inicio' => now(),

            'fecha_vencimiento' => Carbon::now()->addMonths($plazo),

            'monto_cuota' => $montoCuota,

            'estado' => 'aprobado',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Actualizar solicitud
        |--------------------------------------------------------------------------
        */

        $solicitud->update([
            'estado' => 'aprobado',
        ]);

        return redirect()
            ->route('prestamos.show', $prestamo)
            ->with(
                'success',
                'Solicitud aprobada y préstamo creado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | RECHAZAR SOLICITUD
    |--------------------------------------------------------------------------
    */

    public function rechazar(SolicitudPrestamo $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {

            return back()->with(
                'error',
                'Esta solicitud ya fue revisada.'
            );
        }

        $solicitud->update([
            'estado' => 'rechazado',
        ]);

        return redirect()
            ->route('admin.solicitudes.index')
            ->with(
                'success',
                'Solicitud rechazada correctamente.'
            );
    }
}