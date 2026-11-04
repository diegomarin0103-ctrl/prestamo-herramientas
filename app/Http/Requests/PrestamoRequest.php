<?php

namespace App\Http\Requests;

use App\Models\Prestamo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validaciones del formulario de préstamos (crear y editar).
 * La validación de unidades disponibles se hace en el controlador,
 * porque depende del inventario actual de la herramienta.
 */
class PrestamoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'herramienta_id' => ['required', 'integer', 'exists:herramientas,id'],
            'nombre_responsable' => ['required', 'string', 'min:3', 'max:120'],
            'fecha_prestamo' => ['required', 'date', 'before_or_equal:today'],
            'fecha_devolucion' => ['nullable', 'date', 'after_or_equal:fecha_prestamo', 'required_if:estado,devuelto'],
            'cantidad_prestada' => ['required', 'integer', 'min:1', 'max:1000'],
            'estado' => ['required', Rule::in(array_keys(Prestamo::ESTADOS))],
        ];
    }

    public function attributes(): array
    {
        return [
            'herramienta_id' => 'herramienta',
            'nombre_responsable' => 'nombre del responsable',
            'fecha_prestamo' => 'fecha de préstamo',
            'fecha_devolucion' => 'fecha de devolución',
            'cantidad_prestada' => 'cantidad prestada',
            'estado' => 'estado del préstamo',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_prestamo.before_or_equal' => 'La fecha de préstamo no puede ser posterior a hoy.',
            'fecha_devolucion.after_or_equal' => 'La fecha de devolución no puede ser anterior a la fecha de préstamo.',
            'fecha_devolucion.required_if' => 'Indica la fecha de devolución cuando el préstamo está devuelto.',
        ];
    }
}
