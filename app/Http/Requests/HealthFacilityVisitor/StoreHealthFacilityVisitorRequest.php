<?php

namespace App\Http\Requests\HealthFacilityVisitor;

use Illuminate\Foundation\Http\FormRequest;

class StoreHealthFacilityVisitorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:registers,id'],
            'health_facility_id' => ['required', 'integer', 'exists:health_facilities,id'],
            'visit_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User is required.',
            'user_id.exists' => 'Selected user does not exist.',

            'health_facility_id.required' => 'Health facility is required.',
            'health_facility_id.exists' => 'Selected health facility does not exist.',

            'visit_date.date' => 'Visit date must be a valid date.',
        ];
    }
}
