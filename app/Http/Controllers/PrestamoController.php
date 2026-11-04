<?php

namespace App\Http\Controllers;

use App\Models\Herramienta;
use App\Models\Prestamo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrestamoController extends Controller
{
    /** Consultar: lista de préstamos con búsqueda y paginación. */
    public function index(Request $request): View
    {
        $buscar = $request->query('buscar');

        $prestamos = Prestamo::query()
            ->when($buscar, fn ($query, $buscar) => $query->where('nombre_responsable', 'like', "%{$buscar}%"))
            ->orderByDesc('fecha_prestamo')
            ->paginate(10)
            ->withQueryString();

        return view('prestamos.index', compact('prestamos', 'buscar'));
    }

    public function create(): View
    {
        return view('prestamos.create', [
            'prestamo' => new Prestamo(['estado' => 'prestado', 'fecha_prestamo' => now()]),
            'herramientas' => Herramienta::orderBy('nombre')->get(),
        ]);
    }

    /** Crear: registra un préstamo nuevo en MySQL. */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        Prestamo::create($datos);

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo registrado correctamente.');
    }

    public function show(Prestamo $prestamo): View
    {
        return view('prestamos.show', compact('prestamo'));
    }

    public function edit(Prestamo $prestamo): View
    {
        return view('prestamos.edit', [
            'prestamo' => $prestamo,
            'herramientas' => Herramienta::orderBy('nombre')->get(),
        ]);
    }

    /** Actualizar: modifica un préstamo existente. */
    public function update(Request $request, Prestamo $prestamo): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        $prestamo->update($datos);

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo actualizado correctamente.');
    }

    /** Eliminar: borra el préstamo (la confirmación se pide en la vista). */
    public function destroy(Prestamo $prestamo): RedirectResponse
    {
        $prestamo->delete();

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo eliminado correctamente.');
    }

    private function reglas(): array
    {
        return [
            'herramienta_id' => ['required'],
            'nombre_responsable' => ['required', 'string', 'max:120'],
            'fecha_prestamo' => ['required', 'date'],
            'fecha_devolucion' => ['nullable', 'date'],
            'cantidad_prestada' => ['required', 'integer', 'min:1'],
            'estado' => ['required'],
        ];
    }
}
