<?php

namespace App\Http\Requests\Devices;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear un dispositivo
     */
    public function rules(): array
    {
        return [
            'area_id' => 'required|integer|exists:areas,id',
            'name' => 'required|string|max:255',
            'device_key' => 'required|string|unique:devices,device_key',
            'type' => 'sometimes|in:clock,logical,external',
            'status' => 'sometimes|in:active,disabled',
        ];
    }
}

