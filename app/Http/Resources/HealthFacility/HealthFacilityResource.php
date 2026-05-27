<?php

namespace App\Http\Resources\HealthFacility;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthFacilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name_km' => $this->name_km,
            'name_en' => $this->name_en,
            'type' => $this->type,
            'parent_id' => $this->parent_id,

            'province_code' => $this->province_code,
            'district_code' => $this->district_code,
            'commune_code' => $this->commune_code,
            'village_code' => $this->village_code,

            'latitude' => $this->latitude,
            'longitude' => $this->longitude,

            'hf_image' => $this->hf_image,
            'hf_image_url' => $this->hf_image ? asset('storage/' . $this->hf_image) : null,
            
            'director_name' => $this->director_name,
            'contact_phone' => $this->contact_phone,

            'is_active' => (bool) $this->is_active,

            'parent' => $this->whenLoaded('parent', function () {
                return [
                    'id' => $this->parent?->id,
                    'code' => $this->parent?->code,
                    'name_km' => $this->parent?->name_km,
                    'name_en' => $this->parent?->name_en,
                    'type' => $this->parent?->type,
                ];
            }),

            'province' => $this->whenLoaded('province', function () {
                return [
                    'province_code' => $this->province?->province_code,
                    'name_kh' => $this->province?->province_name_kh,
                    'name_en' => $this->province?->province_name_en,
                ];
            }),

            'district' => $this->whenLoaded('district', function () {
                return [
                    'district_code' => $this->district?->district_code,
                    'name_kh' => $this->district?->district_name_kh,
                    'name_en' => $this->district?->district_name_en,
                ];
            }),

            'commune' => $this->whenLoaded('commune', function () {
                return [
                    'commune_code' => $this->commune?->commune_code,
                    'name_kh' => $this->commune?->commune_name_kh,
                    'name_en' => $this->commune?->commune_name_en,
                ];
            }),

            'village' => $this->whenLoaded('village', function () {
                return [
                    'village_code' => $this->village?->village_code,
                    'name_kh' => $this->village?->village_name_kh,
                    'name_en' => $this->village?->village_name_en,
                ];
            }),

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
