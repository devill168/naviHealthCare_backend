<?php

namespace App\Http\Resources\HealthFacilityVisitor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthFacilityVisitorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'health_facility_id' => $this->health_facility_id,
            'visit_date' => optional($this->visit_date)->format('Y-m-d'),

            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user?->id,
                    'username' => $this->user?->username,
                    'email' => $this->user?->email,
                ];
            }),

            'health_facility' => $this->whenLoaded('healthFacility', function () {
                return [
                    'id' => $this->healthFacility?->id,
                    'code' => $this->healthFacility?->code,
                    'name_km' => $this->healthFacility?->name_km,
                    'name_en' => $this->healthFacility?->name_en,
                    'type' => $this->healthFacility?->type,
                ];
            }),

            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
