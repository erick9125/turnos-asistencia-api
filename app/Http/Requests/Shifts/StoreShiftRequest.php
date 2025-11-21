<?php

namespace App\Http\Requests\Shifts;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear un turno
     */
    public function rules(): array
    {
        return [
            'worker_id' => 'required|integer|exists:workers,id',
            'area_id' => 'required|integer|exists:areas,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ];
    }
}

