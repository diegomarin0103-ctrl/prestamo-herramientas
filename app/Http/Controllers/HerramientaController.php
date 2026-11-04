<?php

namespace App\Http\Controllers;

use App\Http\Requests\HerramientaRequest;
use App\Models\Herramienta;
use App\Models\Prestamo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HerramientaController extends Controller
{
    /** Consultar: lista de herramientas con las unidades que están prestadas. */
    public function index(Request $request): View
    {
        $buscar = $request->query('buscar');

        $herramientas = Herramienta::query()
            ->withSum(['prestamos as unidades_prestadas' => fn ($q) => $q->whereIn('estado', Prestamo::ESTADOS_ACTIVOS)], 'cantidad_prestada')
            ->when($buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('codigo', 'like', "%{$buscar}%")
                        ->orWhere('nombre', 'like', "%{$buscar}%")
                        ->orWhere('tipo', 'like', "%{$buscar}%")
                        ->orWhere('marca', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('codigo')
            ->paginate(10)
            ->withQueryString();

        return view('herramientas.index', compact('herramientas', 'buscar'));
    }

    public function create(): View
    {
        return view('herramientas.create', ['herramienta' => new Herramienta(['estado' => 'activa'])]);
    }

    /** Crear: guarda una herramienta nueva en MySQL. */
    public function store(HerramientaRequest $request): RedirectResponse
    {
        Herramienta::create($request->validated());

        return redirect()->route('herramientas.index')
            ->with('success', 'Herramienta registrada correctamente.');
    }

    /** Detalle de la herramienta con su historial de préstamos. */
    public function show(Herramienta $herramienta): View
    {
        $prestamos = $herramienta->prestamos()->orderByDesc('fecha_prestamo')->get();

        return view('herramientas.show', compact('herramienta', 'prestamos'));
    }

    public function edit(Herramienta $herramienta): View
    {
        return view('herramientas.edit', compact('herramienta'));
    }

    /** Actualizar: modifica una herramienta existente. */
    public function update(HerramientaRequest $request, Herramienta $herramienta): RedirectResponse
    {
        $herramienta->update($request->validated());

        return redirect()->route('herramientas.index')
            ->with('success', 'Herramienta actualizada correctamente.');
    }

    /**
     * Eliminar: solo se permite si la herramienta no tiene préstamos
     * (la base de datos también lo impide con la clave foránea).
     */
    public function destroy(Herramienta $herramienta): RedirectResponse
    {
        $total = $herramienta->prestamos()->count();

        if ($total > 0) {
            return redirect()->route('herramientas.index')
                ->with('error', "No se puede eliminar la herramienta {$herramienta->codigo} porque tiene {$total} préstamo(s) registrado(s).");
        }

        $herramienta->delete();

        return redirect()->route('herramientas.index')
            ->with('success', "Herramienta {$herramienta->codigo} eliminada correctamente.");
    }
}
