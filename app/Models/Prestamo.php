<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    /** Estados permitidos para un préstamo. */
    public const ESTADOS = [
        'prestado' => 'Prestado',
        'devuelto' => 'Devuelto',
        'vencido' => 'Vencido',
    ];

    protected $fillable = [
        'herramienta_id',
        'nombre_responsable',
        'fecha_prestamo',
        'fecha_devolucion',
        'cantidad_prestada',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_prestamo' => 'date',
            'fecha_devolucion' => 'date',
            'cantidad_prestada' => 'integer',
        ];
    }

    /** Clase de Bootstrap para pintar el estado. */
    public function colorEstado(): string
    {
        return match ($this->estado) {
            'prestado' => 'primary',
            'devuelto' => 'success',
            'vencido' => 'danger',
            default => 'secondary',
        };
    }
}
