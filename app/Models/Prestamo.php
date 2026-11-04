<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /** Estados en los que la herramienta sigue fuera del taller. */
    public const ESTADOS_ACTIVOS = ['prestado', 'vencido'];

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

    /** Cada préstamo pertenece a una herramienta. */
    public function herramienta(): BelongsTo
    {
        return $this->belongsTo(Herramienta::class);
    }

    /** Indica si las unidades prestadas todavía no han regresado. */
    public function estaActivo(): bool
    {
        return in_array($this->estado, self::ESTADOS_ACTIVOS, true);
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
