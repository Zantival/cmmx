<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|required|in:Preventive,Corrective,Predictive',
            'status' => 'sometimes|required|in:Pending,In Progress,Completed',
            'date' => 'sometimes|required|date',
            'description' => 'sometimes|required|string',
            'equipment_id' => 'sometimes|required|exists:equipment,id',
            'technician_id' => 'sometimes|required|exists:users,id',
        ];
    }
}
