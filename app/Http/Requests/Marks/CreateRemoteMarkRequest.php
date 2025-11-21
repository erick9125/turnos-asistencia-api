<?php

namespace App\Http\Requests\Marks;

use Illuminate\Foundation\Http\FormRequest;

class CreateRemoteMarkRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear una marca remota
     */
    public function rules(): array
    {
        return [
            'worker_id' => 'required|integer|exists:workers,id',
            'device_id' => 'required|integer|exists:devices,id',
            'direction' => 'required|in:in,out',
            'marked_at' => 'required|date',
        ];
    }
}

