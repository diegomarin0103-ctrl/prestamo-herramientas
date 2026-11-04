<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrestamoRequest;
use App\Models\Herramienta;
use App\Models\Prestamo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * CRUD de préstamos.
 *
 * Regla de inventario: mientras un préstamo está "prestado" o "vencido",
 * sus unidades se descuentan de la cantidad disponible de la herramienta.
 * Cuando pasa a "devuelto" o se elimina, las unidades regresan.
 */
class PrestamoController extends Controller
{
    /** Consultar: lista de préstamos con búsqueda, filtros y paginación. */
    public function index(Request $request): View
    {
        $buscar = $request->query('buscar');
        $estado = $request->query('estado');
        $herramientaId = $request->query('herramienta_id');

        $prestamos = Prestamo::with('herramienta')
            ->when($buscar, fn ($query, $buscar) => $query->where('nombre_responsable', 'like', "%{$buscar}%"))
            ->when($estado, fn ($query, $estado) => $query->where('estado', $estado))
            ->when($herramientaId, fn ($query, $id) => $query->where('herramienta_id', $id))
            ->orderByDesc('fecha_prestamo')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $herramientas = Herramienta::orderBy('nombre')->get();

        return view('prestamos.index', compact('prestamos', 'buscar', 'estado', 'herramientaId', 'herramientas'));
    }

    public function create(Request $request): View
    {
        return view('prestamos.create', [
            'prestamo' => new Prestamo([
                'estado' => 'prestado',
                'fecha_prestamo' => now(),
                'herramienta_id' => $request->query('herramienta_id'),
            ]),
            'herramientas' => Herramienta::orderBy('nombre')->get(),
        ]);
    }

    /** Crear: registra el préstamo y descuenta las unidades de la herramienta. */
    public function store(PrestamoRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        DB::transaction(function () use ($datos) {
            $prestamo = new Prestamo($datos);

            if ($prestamo->estaActivo()) {
                $this->descontarUnidades($datos['herramienta_id'], $datos['cantidad_prestada'], true);
            }

            $prestamo->save();
        });

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo registrado correctamente.');
    }

    public function show(Prestamo $prestamo): View
    {
        $prestamo->load('herramienta');

        return view('prestamos.show', compact('prestamo'));
    }

    public function edit(Prestamo $prestamo): View
    {
        return view('prestamos.edit', [
            'prestamo' => $prestamo,
            'herramientas' => Herramienta::orderBy('nombre')->get(),
        ]);
    }

    /** Actualizar: modifica el préstamo y ajusta el inventario según el cambio. */
    public function update(PrestamoRequest $request, Prestamo $prestamo): RedirectResponse
    {
        $datos = $request->validated();

        DB::transaction(function () use ($prestamo, $datos) {
            $estabaActivo = $prestamo->estaActivo();
            $herramientaAnterior = $prestamo->herramienta_id;

            // 1. Se devuelven las unidades que este préstamo tenía descontadas.
            if ($estabaActivo) {
                Herramienta::whereKey($herramientaAnterior)->increment('cantidad_disponible', $prestamo->cantidad_prestada);
            }

            $prestamo->fill($datos);

            // 2. Se descuentan de nuevo según los datos actualizados.
            if ($prestamo->estaActivo()) {
                $mismaHerramientaYaPrestada = $estabaActivo && $herramientaAnterior == $prestamo->herramienta_id;
                $this->descontarUnidades($prestamo->herramienta_id, $prestamo->cantidad_prestada, ! $mismaHerramientaYaPrestada);
            }

            $prestamo->save();
        });

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo actualizado correctamente.');
    }

    /** Eliminar: si el préstamo seguía activo, las unidades regresan al inventario. */
    public function destroy(Prestamo $prestamo): RedirectResponse
    {
        DB::transaction(function () use ($prestamo) {
            if ($prestamo->estaActivo()) {
                Herramienta::whereKey($prestamo->herramienta_id)->increment('cantidad_disponible', $prestamo->cantidad_prestada);
            }

            $prestamo->delete();
        });

        return redirect()->route('prestamos.index')
            ->with('success', "Préstamo #{$prestamo->id} eliminado correctamente.");
    }

    /**
     * Verifica que haya unidades suficientes y las descuenta de la herramienta.
     * Si algo no cumple, lanza un error de validación y la transacción se revierte.
     */
    private function descontarUnidades(int $herramientaId, int $cantidad, bool $exigirActiva): void
    {
        $herramienta = Herramienta::lockForUpdate()->findOrFail($herramientaId);

        if ($exigirActiva && $herramienta->estado !== 'activa') {
            throw ValidationException::withMessages([
                'herramienta_id' => "La herramienta {$herramienta->nombre} no se puede prestar porque está ".mb_strtolower(Herramienta::ESTADOS[$herramienta->estado]).'.',
            ]);
        }

        if ($cantidad > $herramienta->cantidad_disponible) {
            throw ValidationException::withMessages([
                'cantidad_prestada' => "Solo hay {$herramienta->cantidad_disponible} unidad(es) disponible(s) de {$herramienta->nombre}.",
            ]);
        }

        $herramienta->decrement('cantidad_disponible', $cantidad);
    }
}
