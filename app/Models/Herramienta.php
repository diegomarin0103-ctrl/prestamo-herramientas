<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Herramienta extends Model
{
    use HasFactory;

    protected $table = 'herramientas';

    /** Estados permitidos para una herramienta. */
    public const ESTADOS = [
        'activa' => 'Activa',
        'en_reparacion' => 'En reparación',
        'fuera_de_servicio' => 'Fuera de servicio',
    ];

    /** Tipos de herramienta sugeridos en el formulario. */
    public const TIPOS = ['Manual', 'Eléctrica', 'Neumática', 'Medición', 'Corte', 'Soldadura'];

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'marca',
        'cantidad_disponible',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_disponible' => 'integer',
        ];
    }

    /** Una herramienta puede tener muchos préstamos. */
    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class);
    }

    /** Clase de Bootstrap para pintar el estado. */
    public function colorEstado(): string
    {
        return match ($this->estado) {
            'activa' => 'success',
            'en_reparacion' => 'warning',
            'fuera_de_servicio' => 'secondary',
            default => 'light',
        };
    }
}
