<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HealthFacility;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $rawCounts = HealthFacility::query()
            ->where('is_active', true)
            ->select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        $types = ['PHD', 'NH', 'PH', 'OD', 'RH', 'HC', 'HP'];

        $summaryByType = [];
        foreach ($types as $type) {
            $summaryByType[$type] = (int) ($rawCounts[$type] ?? 0);
        }

        $total = HealthFacility::count();
        $active = HealthFacility::where('is_active', true)->count();
        $inactive = HealthFacility::where('is_active', false)->count();

        return response()->json([
            'message' => 'Health facility dashboard fetched successfully.',
            'data' => [
                'total_health_facilities' => $total,
                'active_health_facilities' => $active,
                'inactive_health_facilities' => $inactive,
                'active_by_type' => $summaryByType,
            ],
        ]);
    }

}
