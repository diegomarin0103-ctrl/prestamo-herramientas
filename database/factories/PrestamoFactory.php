<?php

namespace Database\Factories;

use App\Models\Herramienta;
use App\Models\Prestamo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prestamo>
 */
class PrestamoFactory extends Factory
{
    protected $model = Prestamo::class;

    public function definition(): array
    {
        return [
            'herramienta_id' => Herramienta::factory(),
            'nombre_responsable' => fake()->name(),
            'fecha_prestamo' => now()->subDays(3)->toDateString(),
            'fecha_devolucion' => now()->addDays(4)->toDateString(),
            'cantidad_prestada' => 1,
            'estado' => 'prestado',
        ];
    }
}
