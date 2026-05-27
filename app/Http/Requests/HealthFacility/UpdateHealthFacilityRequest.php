<?php

namespace App\Http\Requests\HealthFacility;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHealthFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $healthFacilityId = $this->route('healthFacility')?->id ?? $this->route('healthFacility');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('health_facilities', 'code')->ignore($healthFacilityId),
            ],
            'name_en' => ['nullable', 'string', 'max:255'],
            'name_km' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['PHD', 'NH', 'PH', 'OD', 'RH', 'HC', 'HP'])],
            'parent_id' => ['nullable', 'exists:health_facilities,id'],
            'province_code' => ['nullable', 'exists:provinces,province_code'],
            'district_code' => ['nullable', 'exists:districts,district_code'],
            'commune_code' => ['nullable', 'exists:communes,commune_code'],
            'village_code' => ['nullable', 'exists:villages,village_code'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'hf_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'director_name' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'remove_hf_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
