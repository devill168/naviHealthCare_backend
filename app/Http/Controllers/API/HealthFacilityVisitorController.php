<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HealthFacilityVisitor\StoreHealthFacilityVisitorRequest;
use App\Http\Requests\HealthFacilityVisitor\UpdateHealthFacilityVisitorRequest;
use App\Http\Resources\HealthFacilityVisitor\HealthFacilityVisitorResource;
use App\Models\HealthFacilityVisitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthFacilityVisitorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = HealthFacilityVisitor::query()
            ->with([
                'user:id,username,email',
                'healthFacility:id,code,name_km,name_en,type',
            ]);

//        if ($request->filled('user_id')) {
//            $query->where('user_id', $request->integer('user_id'));
//        }

        if ($request->filled('health_facility_id')) {
            $query->where('health_facility_id', $request->integer('health_facility_id'));
        }

        if ($request->filled('visit_date')) {
            $query->whereDate('visit_date', $request->visit_date);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('visit_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('visit_date', '<=', $request->to_date);
        }

        $perPage = (int) $request->input('per_page', 10);
        $perPage = $perPage > 0 ? min($perPage, 100) : 10;

        $paginator = $query
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'message' => 'Health facility visitors fetched successfully.',
            'data' => HealthFacilityVisitorResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ]);
    }

    public function store(StoreHealthFacilityVisitorRequest $request): JsonResponse
    {
        $visitor = HealthFacilityVisitor::create([
            'user_id' => $request->user_id,
            'health_facility_id' => $request->health_facility_id,
            'visit_date' => $request->visit_date ?? now()->toDateString(),
        ]);

        $visitor->load([
            'user:id,username,email',
            'healthFacility:id,code,name_km,name_en,type',
        ]);

        return response()->json([
            'message' => 'Health facility visitor created successfully.',
            'data' => new HealthFacilityVisitorResource($visitor),
        ], 201);
    }

    public function show(HealthFacilityVisitor $healthFacilityVisitor): JsonResponse
    {
        $healthFacilityVisitor->load([
            'user:id,username,email',
            'healthFacility:id,code,name_km,name_en,type',
        ]);

        return response()->json([
            'message' => 'Health facility visitor fetched successfully.',
            'data' => new HealthFacilityVisitorResource($healthFacilityVisitor),
        ]);
    }

    public function update(
        UpdateHealthFacilityVisitorRequest $request,
        HealthFacilityVisitor $healthFacilityVisitor
    ): JsonResponse {
        $healthFacilityVisitor->update([
            'user_id' => $request->has('user_id')
                ? $request->user_id
                : $healthFacilityVisitor->user_id,

            'health_facility_id' => $request->has('health_facility_id')
                ? $request->health_facility_id
                : $healthFacilityVisitor->health_facility_id,

            'visit_date' => $request->has('visit_date')
                ? $request->visit_date
                : $healthFacilityVisitor->visit_date,
        ]);

        $healthFacilityVisitor->load([
            'user:id,name,email',
            'healthFacility:id,code,name_km,name_en,type',
        ]);

        return response()->json([
            'message' => 'Health facility visitor updated successfully.',
            'data' => new HealthFacilityVisitorResource($healthFacilityVisitor),
        ]);
    }

    public function destroy(HealthFacilityVisitor $healthFacilityVisitor): JsonResponse
    {
        $healthFacilityVisitor->delete();

        return response()->json([
            'message' => 'Health facility visitor deleted successfully.',
        ]);
    }
}
