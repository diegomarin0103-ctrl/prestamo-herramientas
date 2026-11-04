<?php

namespace App\Http\Requests;

use App\Models\Herramienta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validaciones del formulario de herramientas (crear y editar).
 */
class HerramientaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Al editar, la propia herramienta se excluye de la validación de "único".
        $herramientaId = $this->route('herramienta')?->id;

        return [
            'codigo' => ['required', 'string', 'max:30', Rule::unique('herramientas', 'codigo')->ignore($herramientaId)],
            'nombre' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'string', 'max:60'],
            'marca' => ['required', 'string', 'max:60'],
            'cantidad_disponible' => ['required', 'integer', 'min:0', 'max:100000'],
            'estado' => ['required', Rule::in(array_keys(Herramienta::ESTADOS))],
        ];
    }

    public function attributes(): array
    {
        return [
            'codigo' => 'código',
            'nombre' => 'nombre',
            'tipo' => 'tipo de herramienta',
            'marca' => 'marca',
            'cantidad_disponible' => 'cantidad disponible',
            'estado' => 'estado',
        ];
    }
}
