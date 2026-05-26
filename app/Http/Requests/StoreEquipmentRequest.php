<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para crear un nuevo equipo/activo industrial.
 * La autorización por rol se maneja en el router (has.role:Admin).
 */
class StoreEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // El router ya garantiza que solo Admin llega aquí
    }

    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'code'                  => 'required|string|max:50|unique:equipment,code',
            'brand'                 => 'nullable|string|max:100',
            'model'                 => 'nullable|string|max:100',
            'serial_number'         => 'nullable|string|max:100',
            'category'              => 'required|string|max:100',
            'location'              => 'nullable|string|max:255',
            'status'                => 'required|in:Operational,In Repair,Out of Service',
            'criticality'           => 'required|in:Critical,High,Medium,Low',
            'installation_date'     => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'warranty_expiry'       => 'nullable|date',
            'notes'                 => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => __('El nombre del equipo es obligatorio.'),
            'code.required'        => __('El código del activo es obligatorio.'),
            'code.unique'          => __('Ya existe un equipo con ese código.'),
            'category.required'    => __('La categoría es obligatoria.'),
            'status.required'      => __('El estado es obligatorio.'),
            'criticality.required' => __('La criticidad es obligatoria.'),
        ];
    }
}
