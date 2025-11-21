<?php

namespace App\Http\Requests\Shifts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para actualizar un turno
     */
    public function rules(): array
    {
        return [
            'worker_id' => 'sometimes|integer|exists:workers,id',
            'area_id' => 'sometimes|integer|exists:areas,id',
            'start_at' => 'sometimes|date',
            'end_at' => 'sometimes|date|after:start_at',
            'status' => 'sometimes|in:planned,in_progress,completed,inconsistent,absent',
        ];
    }
}

