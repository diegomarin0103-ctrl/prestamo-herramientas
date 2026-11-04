<?php

namespace App\Http\Controllers;

use App\Models\Herramienta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HerramientaController extends Controller
{
    /** Consultar: lista de herramientas con búsqueda y paginación. */
    public function index(Request $request): View
    {
        $buscar = $request->query('buscar');

        $herramientas = Herramienta::query()
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
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        Herramienta::create($datos);

        return redirect()->route('herramientas.index')
            ->with('success', 'Herramienta registrada correctamente.');
    }

    public function show(Herramienta $herramienta): View
    {
        return view('herramientas.show', compact('herramienta'));
    }

    public function edit(Herramienta $herramienta): View
    {
        return view('herramientas.edit', compact('herramienta'));
    }

    /** Actualizar: modifica una herramienta existente. */
    public function update(Request $request, Herramienta $herramienta): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        $herramienta->update($datos);

        return redirect()->route('herramientas.index')
            ->with('success', 'Herramienta actualizada correctamente.');
    }

    /** Eliminar: borra la herramienta (la confirmación se pide en la vista). */
    public function destroy(Herramienta $herramienta): RedirectResponse
    {
        $herramienta->delete();

        return redirect()->route('herramientas.index')
            ->with('success', 'Herramienta eliminada correctamente.');
    }

    private function reglas(): array
    {
        return [
            'codigo' => ['required', 'string', 'max:30'],
            'nombre' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'string', 'max:60'],
            'marca' => ['required', 'string', 'max:60'],
            'cantidad_disponible' => ['required', 'integer', 'min:0'],
            'estado' => ['required'],
        ];
    }
}
