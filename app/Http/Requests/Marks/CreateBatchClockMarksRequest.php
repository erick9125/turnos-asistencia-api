<?php

namespace App\Http\Requests\Marks;

use Illuminate\Foundation\Http\FormRequest;

class CreateBatchClockMarksRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear marcas batch desde reloj
     */
    public function rules(): array
    {
        return [
            'marks' => 'required|array|min:1',
            'marks.*.worker_rut' => 'required|string',
            'marks.*.device_key' => 'required|string',
            'marks.*.direction' => 'required|in:in,out',
            'marks.*.marked_at' => 'required|date',
        ];
    }
}

