<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Village;
use App\Models\Province;
use App\Models\District;
use App\Models\Commune;
use App\Models\Od;
use App\Models\Hc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VillageController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);

        $villages = Village::with([
            'province',
            'district',
            'commune',
        ])
        ->latest()
        ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $villages->items(),
            'meta' => [
                'current_page' => $villages->currentPage(),
                'last_page' => $villages->lastPage(),
                'per_page' => $villages->perPage(),
                'total' => $villages->total(),
            ],
        ]);
    }

    public function getVillage()
    {
        $villages = Village::with([
            'province',
            'district',
            'commune',
            'od',
            'hc',
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $villages
        ]);
    }

   public function nextCode(string $commune_code)
{
    if (blank($commune_code)) {
        return response()->json([
            'success' => false,
            'message' => 'Commune code is required.',
        ], 422);
    }

    $lastVillage = Village::query()
        ->where('commune_code', $commune_code)
        ->where('village_code', 'like', $commune_code . '%')
        ->orderByDesc('village_code')
        ->first();

    $nextNumber = 1;

    if ($lastVillage) {
        $suffix = substr((string) $lastVillage->village_code, strlen($commune_code));

        if (is_numeric($suffix)) {
            $nextNumber = ((int) $suffix) + 1;
        }
    }

    $nextCode = $commune_code . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

    return response()->json([
        'success' => true,
        'village_code' => $nextCode,
    ]);
}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'province_code'   => ['required', 'exists:provinces,province_code'],
            'district_code'   => ['required', 'exists:districts,district_code'],
            'commune_code'    => ['required', 'exists:communes,commune_code'],
            'village_name_en' => ['required', 'string', 'max:255'],
            'village_name_kh' => ['required', 'string', 'max:255'],
            'latitude'        => ['nullable', 'numeric'],
            'longitude'       => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $province = Province::where('province_code', $request->province_code)->first();
        $district = District::where('district_code', $request->district_code)->first();
        $commune  = Commune::where('commune_code', $request->commune_code)->first();

        if (!$province || !$district || !$commune) {
            return response()->json([
                'success' => false,
                'message' => 'Related data not found'
            ], 404);
        }

        if ($district->province_code !== $province->province_code) {
            return response()->json([
                'success' => false,
                'message' => 'District does not belong to selected province'
            ], 422);
        }

        if ($commune->district_code !== $district->district_code) {
            return response()->json([
                'success' => false,
                'message' => 'Commune does not belong to selected district'
            ], 422);
        }

        $villageCode = $this->generateVillageCode($request->village_code);

        $village = Village::create([
            'province_code'   => $request->province_code,
            'district_code'   => $request->district_code,
            'commune_code'    => $request->commune_code,
            'village_name_en' => $request->village_name_en,
            'village_name_kh' => $request->village_name_kh,
            'village_code'    => $villageCode,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
            'visit'           => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Village created successfully',
            'data' => $village->load(['province', 'district', 'commune'])
        ], 201);
    }

    public function show($id)
    {
        $village = Village::with(['province', 'district', 'commune'])->find($id);

        if (!$village) {
            return response()->json([
                'success' => false,
                'message' => 'Village not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $village
        ]);
    }

    public function update(Request $request, $id)
    {
        $village = Village::find($id);

        if (!$village) {
            return response()->json([
                'success' => false,
                'message' => 'Village not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'province_code'   => ['required', 'exists:provinces,province_code'],
            'district_code'   => ['required', 'exists:districts,district_code'],
            'commune_code'    => ['required', 'exists:communes,commune_code'],
            'village_name_en' => ['required', 'string', 'max:255'],
            'village_name_kh' => ['required', 'string', 'max:255'],
            'latitude'        => ['nullable', 'numeric'],
            'longitude'       => ['nullable', 'numeric'],
            'village_code'    => [
                'required',
                'string',
                'max:20',
                Rule::unique('villages', 'village_code')->ignore($id),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $province = Province::where('province_code', $request->province_code)->first();
        $district = District::where('district_code', $request->district_code)->first();
        $commune  = Commune::where('commune_code', $request->commune_code)->first();

        if (!$province || !$district || !$commune) {
            return response()->json([
                'success' => false,
                'message' => 'Related data not found'
            ], 404);
        }

        if ($district->province_code !== $province->province_code) {
            return response()->json([
                'success' => false,
                'message' => 'District does not belong to selected province'
            ], 422);
        }

        if ($commune->district_code !== $district->district_code) {
            return response()->json([
                'success' => false,
                'message' => 'Commune does not belong to selected district'
            ], 422);
        }

        $village->update([
            'province_code'   => $request->province_code,
            'district_code'   => $request->district_code,
            'commune_code'    => $request->commune_code,
            'village_name_en' => $request->village_name_en,
            'village_name_kh' => $request->village_name_kh,
            'village_code'    => $request->village_code,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Village updated successfully',
            'data' => $village->load(['province', 'district', 'commune'])
        ]);
    }

    public function destroy($id)
    {
        $village = Village::find($id);

        if (!$village) {
            return response()->json([
                'success' => false,
                'message' => 'Village not found'
            ], 404);
        }

        $village->delete();

        return response()->json([
            'success' => true,
            'message' => 'Village deleted successfully'
        ]);
    }

    private function generateVillageCode($hcCode)
    {
        $lastVillage = Village::where('village_code', $hcCode)
            ->orderBy('village_code', 'desc')
            ->first();

        $nextNumber = 1;

        if ($lastVillage) {
            $lastNumber = (int) substr($lastVillage->village_code, -2);
            $nextNumber = $lastNumber + 1;
        }

        return $hcCode . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}
