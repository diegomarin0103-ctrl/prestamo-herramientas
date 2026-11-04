<?php

namespace Tests\Feature;

use App\Models\Herramienta;
use App\Models\Prestamo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HerramientaCrudTest extends TestCase
{
    use RefreshDatabase;

    private function datosValidos(array $cambios = []): array
    {
        return array_merge([
            'codigo' => 'HR-100',
            'nombre' => 'Taladro percutor',
            'tipo' => 'Eléctrica',
            'marca' => 'Bosch',
            'cantidad_disponible' => 3,
            'estado' => 'activa',
        ], $cambios);
    }

    public function test_muestra_el_listado_de_herramientas(): void
    {
        Herramienta::factory()->create(['nombre' => 'Pulidora angular']);

        $this->get(route('herramientas.index'))
            ->assertOk()
            ->assertSee('Pulidora angular');
    }

    public function test_crea_una_herramienta(): void
    {
        $this->post(route('herramientas.store'), $this->datosValidos())
            ->assertRedirect(route('herramientas.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('herramientas', ['codigo' => 'HR-100', 'cantidad_disponible' => 3]);
    }

    public function test_valida_campos_obligatorios(): void
    {
        $this->post(route('herramientas.store'), [])
            ->assertSessionHasErrors(['codigo', 'nombre', 'tipo', 'marca', 'cantidad_disponible', 'estado']);
    }

    public function test_valida_tipos_de_datos(): void
    {
        $this->post(route('herramientas.store'), $this->datosValidos([
            'cantidad_disponible' => 'tres',
            'estado' => 'perdida',
        ]))->assertSessionHasErrors(['cantidad_disponible', 'estado']);

        $this->post(route('herramientas.store'), $this->datosValidos(['cantidad_disponible' => -1]))
            ->assertSessionHasErrors('cantidad_disponible');

        $this->assertDatabaseCount('herramientas', 0);
    }

    public function test_el_codigo_debe_ser_unico(): void
    {
        Herramienta::factory()->create(['codigo' => 'HR-100']);

        $this->post(route('herramientas.store'), $this->datosValidos())
            ->assertSessionHasErrors('codigo');
    }

    public function test_actualiza_una_herramienta(): void
    {
        $herramienta = Herramienta::factory()->create();

        $this->put(route('herramientas.update', $herramienta), $this->datosValidos([
            'codigo' => $herramienta->codigo,
            'estado' => 'en_reparacion',
        ]))->assertRedirect(route('herramientas.index'));

        $this->assertDatabaseHas('herramientas', ['id' => $herramienta->id, 'estado' => 'en_reparacion']);
    }

    public function test_elimina_una_herramienta_sin_prestamos(): void
    {
        $herramienta = Herramienta::factory()->create();

        $this->delete(route('herramientas.destroy', $herramienta))
            ->assertRedirect(route('herramientas.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('herramientas', ['id' => $herramienta->id]);
    }

    public function test_no_elimina_una_herramienta_con_prestamos(): void
    {
        $prestamo = Prestamo::factory()->create();

        $this->delete(route('herramientas.destroy', $prestamo->herramienta_id))
            ->assertRedirect(route('herramientas.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('herramientas', ['id' => $prestamo->herramienta_id]);
    }
}
