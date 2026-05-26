<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:Preventive,Corrective,Predictive',
            'status' => 'required|in:Pending,In Progress,Completed',
            'date' => 'required|date',
            'description' => 'required|string',
            'equipment_id' => 'required|exists:equipment,id',
            'technician_id' => 'required|exists:users,id',
        ];
    }
}
