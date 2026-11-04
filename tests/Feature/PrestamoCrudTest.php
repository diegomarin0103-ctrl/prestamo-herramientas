<?php

namespace Tests\Feature;

use App\Models\Herramienta;
use App\Models\Prestamo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestamoCrudTest extends TestCase
{
    use RefreshDatabase;

    private function datosValidos(Herramienta $herramienta, array $cambios = []): array
    {
        return array_merge([
            'herramienta_id' => $herramienta->id,
            'nombre_responsable' => 'Carlos Ramírez',
            'fecha_prestamo' => now()->toDateString(),
            'fecha_devolucion' => now()->addWeek()->toDateString(),
            'cantidad_prestada' => 2,
            'estado' => 'prestado',
        ], $cambios);
    }

    public function test_muestra_el_listado_con_la_herramienta(): void
    {
        $prestamo = Prestamo::factory()->create(['nombre_responsable' => 'Laura Gómez']);

        $this->get(route('prestamos.index'))
            ->assertOk()
            ->assertSee('Laura Gómez')
            ->assertSee($prestamo->herramienta->nombre);
    }

    public function test_crea_un_prestamo_y_descuenta_unidades(): void
    {
        $herramienta = Herramienta::factory()->create(['cantidad_disponible' => 5]);

        $this->post(route('prestamos.store'), $this->datosValidos($herramienta))
            ->assertRedirect(route('prestamos.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('prestamos', ['herramienta_id' => $herramienta->id, 'cantidad_prestada' => 2]);
        $this->assertSame(3, $herramienta->fresh()->cantidad_disponible);
    }

    public function test_no_presta_mas_unidades_de_las_disponibles(): void
    {
        $herramienta = Herramienta::factory()->create(['cantidad_disponible' => 1]);

        $this->post(route('prestamos.store'), $this->datosValidos($herramienta, ['cantidad_prestada' => 2]))
            ->assertSessionHasErrors('cantidad_prestada');

        $this->assertDatabaseCount('prestamos', 0);
        $this->assertSame(1, $herramienta->fresh()->cantidad_disponible);
    }

    public function test_no_presta_herramientas_que_no_estan_activas(): void
    {
        $herramienta = Herramienta::factory()->create(['estado' => 'en_reparacion']);

        $this->post(route('prestamos.store'), $this->datosValidos($herramienta))
            ->assertSessionHasErrors('herramienta_id');

        $this->assertDatabaseCount('prestamos', 0);
    }

    public function test_valida_campos_obligatorios_y_tipos(): void
    {
        $this->post(route('prestamos.store'), [])
            ->assertSessionHasErrors(['herramienta_id', 'nombre_responsable', 'fecha_prestamo', 'cantidad_prestada', 'estado']);

        $herramienta = Herramienta::factory()->create();

        $this->post(route('prestamos.store'), $this->datosValidos($herramienta, [
            'herramienta_id' => 9999,
            'cantidad_prestada' => 0,
            'fecha_prestamo' => 'mañana',
            'estado' => 'perdido',
        ]))->assertSessionHasErrors(['herramienta_id', 'cantidad_prestada', 'fecha_prestamo', 'estado']);

        $this->post(route('prestamos.store'), $this->datosValidos($herramienta, [
            'fecha_devolucion' => now()->subDays(2)->toDateString(), // antes del préstamo
        ]))->assertSessionHasErrors('fecha_devolucion');
    }

    public function test_al_devolver_regresan_las_unidades(): void
    {
        $herramienta = Herramienta::factory()->create(['cantidad_disponible' => 5]);
        $this->post(route('prestamos.store'), $this->datosValidos($herramienta));
        $prestamo = Prestamo::first();

        $this->put(route('prestamos.update', $prestamo), $this->datosValidos($herramienta, ['estado' => 'devuelto']))
            ->assertRedirect(route('prestamos.index'));

        $this->assertDatabaseHas('prestamos', ['id' => $prestamo->id, 'estado' => 'devuelto']);
        $this->assertSame(5, $herramienta->fresh()->cantidad_disponible);
    }

    public function test_al_editar_la_cantidad_se_ajusta_el_inventario(): void
    {
        $herramienta = Herramienta::factory()->create(['cantidad_disponible' => 5]);
        $this->post(route('prestamos.store'), $this->datosValidos($herramienta)); // quedan 3
        $prestamo = Prestamo::first();

        $this->put(route('prestamos.update', $prestamo), $this->datosValidos($herramienta, ['cantidad_prestada' => 4]));
        $this->assertSame(1, $herramienta->fresh()->cantidad_disponible);

        // Pedir 6 no es posible: solo existen 5 en total.
        $this->put(route('prestamos.update', $prestamo), $this->datosValidos($herramienta, ['cantidad_prestada' => 6]))
            ->assertSessionHasErrors('cantidad_prestada');
        $this->assertSame(1, $herramienta->fresh()->cantidad_disponible);
        $this->assertSame(4, $prestamo->fresh()->cantidad_prestada);
    }

    public function test_al_eliminar_un_prestamo_activo_regresan_las_unidades(): void
    {
        $herramienta = Herramienta::factory()->create(['cantidad_disponible' => 5]);
        $this->post(route('prestamos.store'), $this->datosValidos($herramienta));
        $prestamo = Prestamo::first();

        $this->delete(route('prestamos.destroy', $prestamo))
            ->assertRedirect(route('prestamos.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('prestamos', ['id' => $prestamo->id]);
        $this->assertSame(5, $herramienta->fresh()->cantidad_disponible);
    }
}
