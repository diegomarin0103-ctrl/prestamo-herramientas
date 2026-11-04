<?php

namespace Database\Factories;

use App\Models\Herramienta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Herramienta>
 */
class HerramientaFactory extends Factory
{
    protected $model = Herramienta::class;

    public function definition(): array
    {
        return [
            'codigo' => 'HR-'.fake()->unique()->numberBetween(100, 99999),
            'nombre' => fake()->randomElement(['Taladro', 'Martillo', 'Pulidora', 'Llave inglesa']).' '.fake()->word(),
            'tipo' => fake()->randomElement(Herramienta::TIPOS),
            'marca' => fake()->randomElement(['Bosch', 'DeWalt', 'Stanley', 'Truper']),
            'cantidad_disponible' => 5,
            'estado' => 'activa',
        ];
    }
}
