<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HealthFacility\StoreHealthFacilityRequest;
use App\Http\Requests\HealthFacility\UpdateHealthFacilityRequest;
use App\Http\Resources\HealthFacility\HealthFacilityResource;
use App\Models\HealthFacility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HealthFacilityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);

        $healthFacilities = HealthFacility::query()
            ->with(['parent', 'province', 'district', 'commune', 'village'])
            ->filter($request->only([
                'search',
                'type',
                'parent_id',
                'province_code',
                'district_code',
                'commune_code',
                'village_code',
                'is_active',
            ]))
//            ->orderBy('code', 'ASC')
            ->latest('id')
            ->paginate($perPage);

       return response()->json([
            'message' => 'Health facilities fetched successfully.',
            'data' => HealthFacilityResource::collection($healthFacilities->items()),
            'meta' => [
                'current_page' => $healthFacilities->currentPage(),
                'last_page' => $healthFacilities->lastPage(),
                'per_page' => $healthFacilities->perPage(),
                'total' => $healthFacilities->total(),
                'from' => $healthFacilities->firstItem(),
                'to' => $healthFacilities->lastItem(),
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function store(StoreHealthFacilityRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('hf_image')) {
            $data['hf_image'] = $request->file('hf_image')->store('health-facilities', 'public');
        }

        $healthFacility = HealthFacility::create($data);

        $healthFacility->load(['parent', 'province', 'district', 'commune', 'village']);

        return response()->json([
            'message' => 'Health facility created successfully.',
            'data' => new HealthFacilityResource($healthFacility),
        ], 201);
    }

    public function show(HealthFacility $healthFacility): JsonResponse
    {
        $healthFacility->load(['parent', 'children', 'province', 'district', 'commune', 'village']);

        return response()->json([
            'message' => 'Health facility detail fetched successfully.',
            'data' => new HealthFacilityResource($healthFacility),
        ]);
    }

    public function update(UpdateHealthFacilityRequest $request, HealthFacility $healthFacility): JsonResponse
    {
        $data = $request->validated();

        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        if ($request->boolean('remove_hf_image')) {
            if ($healthFacility->hf_image && Storage::disk('public')->exists($healthFacility->hf_image)) {
                Storage::disk('public')->delete($healthFacility->hf_image);
            }

            $data['hf_image'] = null;
        }

        if ($request->hasFile('hf_image')) {
            if ($healthFacility->hf_image && Storage::disk('public')->exists($healthFacility->hf_image)) {
                Storage::disk('public')->delete($healthFacility->hf_image);
            }

            $data['hf_image'] = $request->file('hf_image')->store('health-facilities', 'public');
        }

        $healthFacility->update($data);

        $healthFacility->load(['parent', 'province', 'district', 'commune', 'village']);

        return response()->json([
            'message' => 'Health facility updated successfully.',
            'data' => new HealthFacilityResource($healthFacility),
        ]);
    }

    public function destroy(HealthFacility $healthFacility): JsonResponse
    {
        if ($healthFacility->hf_image && Storage::disk('public')->exists($healthFacility->hf_image)) {
            Storage::disk('public')->delete($healthFacility->hf_image);
        }

        $healthFacility->delete();

        return response()->json([
            'message' => 'Health facility deleted successfully.',
        ]);
    }

    public function findLocation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'province_code' => ['nullable', 'string', 'max:20'],
            'district_code' => ['nullable', 'string', 'max:20'],
            'commune_code' => ['nullable', 'string', 'max:20'],
            'village_code' => ['nullable', 'string', 'max:20'],

            'type' => ['nullable', 'in:PHD,NH,PH,OD,RH,HC,HP'],
            'healthFacilityType' => ['nullable', 'in:PHD,NH,PH,OD,RH,HC,HP'],

            'is_active' => ['nullable', 'boolean'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $facilityType = $validated['healthFacilityType'] ?? $validated['type'] ?? null;
        $limit = (int) ($validated['limit'] ?? 20);

        $hasFilter =
            !empty($validated['province_code']) ||
            !empty($validated['district_code']) ||
            !empty($validated['commune_code']) ||
            !empty($validated['village_code']) ||
            !empty($facilityType);

        if (!$hasFilter) {
            return response()->json([
                'message' => 'Please provide at least one filter: province_code, district_code, commune_code, village_code, or type.',
                'errors' => [
                    'filters' => [
                        'At least one filter is required.'
                    ]
                ]
            ], 422);
        }

        $healthFacilities = HealthFacility::query()
            ->with(['parent', 'province', 'district', 'commune', 'village'])
            ->when(!empty($validated['province_code']), function ($q) use ($validated) {
                $q->where('province_code', $validated['province_code']);
            })
            ->when(!empty($validated['district_code']), function ($q) use ($validated) {
                $q->where('district_code', $validated['district_code']);
            })
            ->when(!empty($validated['commune_code']), function ($q) use ($validated) {
                $q->where('commune_code', $validated['commune_code']);
            })
            ->when(!empty($validated['village_code']), function ($q) use ($validated) {
                $q->where('village_code', $validated['village_code']);
            })
            ->when($facilityType, function ($q) use ($facilityType) {
                $q->where('type', $facilityType);
            })
            ->when(array_key_exists('is_active', $validated), function ($q) use ($validated) {
                $q->where('is_active', filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN));
            })
            ->orderBy('type')
            ->orderBy('province_code')
            ->orderBy('district_code')
            ->orderBy('commune_code')
            ->orderBy('village_code')
            ->orderBy('name_en')
//            ->limit($limit)
            ->get();
         return response()->json([
        'message' => 'Health facilities fetched successfully.',
        'data' => HealthFacilityResource::collection($healthFacilities),
    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

}
