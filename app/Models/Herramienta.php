<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
