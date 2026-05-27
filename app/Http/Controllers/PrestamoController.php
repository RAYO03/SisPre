<?php
namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Cliente;
use App\Http\Requests\PrestamoRequest;
use App\Services\AmortizacionService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class PrestamoController extends Controller
{
    public function __construct(private AmortizacionService $amortizacion) {}

    public function index()
    {
        $prestamos = Prestamo::with('cliente')->paginate(15);
        return view('prestamos.index', compact('prestamos'));
    }

    public function create()
    {       
        $clientes = Cliente::orderBy('nombre')->get();
        return view('prestamos.create', compact('clientes'));
    }

    public function store(PrestamoRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['monto_cuota'] = $this->amortizacion->calcularCuotaFija(
                $data['capital'],
                $data['tasa_anual'],
                $data['plazo_meses']
            );

            $data['fecha_vencimiento'] = Carbon::parse($data['fecha_inicio'])
                                        ->addMonths((int) $data['plazo_meses']);
            
                                        $data['estado'] = 'solicitado';

            $prestamo = Prestamo::create($data);
            $this->amortizacion->guardarTabla($prestamo);
        });

        return redirect()->route('prestamos.index')->with('success', 'Préstamo creado y tabla generada.');
    }

    public function show(Prestamo $prestamo)
    {
        $prestamo->load(['cliente', 'cuotas', 'pagos']);
        return view('prestamos.show', compact('prestamo'));
    }

    public function aprobar(Prestamo $prestamo)
    {
        abort_if($prestamo->estado !== 'solicitado', 403);
        $prestamo->update(['estado' => 'aprobado']);
        return back()->with('success', 'Préstamo aprobado.');
    }

    public function activar(Prestamo $prestamo)
    {
        abort_if($prestamo->estado !== 'aprobado', 403);
        $prestamo->update(['estado' => 'activo']);
        return back()->with('success', 'Préstamo activado.');
    }
}