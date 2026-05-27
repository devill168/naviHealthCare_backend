<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DistrictController extends Controller
{
    public function index(Request $request)
{
    try {
        $perPage = (int) $request->get('per_page', 15);
        $search = trim((string) $request->get('search', ''));

        $districts = District::query()
            ->with('province')
            ->when($search, function ($query) use ($search) {
                $query->where('district_name_en', 'like', "%{$search}%")
                    ->orWhere('district_name_kh', 'like', "%{$search}%")
                    ->orWhere('district_code', 'like', "%{$search}%")
                    ->orWhereHas('province', function ($q) use ($search) {
                        $q->where('province_name_en', 'like', "%{$search}%")
                          ->orWhere('province_name_kh', 'like', "%{$search}%")
                          ->orWhere('province_code', 'like', "%{$search}%");
                    });
            })
            ->orderBy('province_code')
            ->orderBy('district_code')
            ->paginate($perPage)
            ->appends($request->query());

        return response()->json([
            'success' => true,
            'data' => $districts->items(),
            'pagination' => [
                'current_page' => $districts->currentPage(),
                'last_page' => $districts->lastPage(),
                'per_page' => $districts->perPage(),
                'total' => $districts->total(),
                'from' => $districts->firstItem(),
                'to' => $districts->lastItem(),
                'has_more_pages' => $districts->hasMorePages(),
            ],
        ], 200);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to load districts.',
            'error' => $e->getMessage(),
        ], 500);
    }
    }
    public function getDistrict()
    {
        $districts = District::with('province')
            ->orderBy('province_code')
            ->orderBy('district_code')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }

    public function nextCode($province_code)
    {
        $province = Province::where('province_code', $province_code)->first();

        if (!$province) {
            return response()->json([
                'success' => false,
                'message' => 'Province not found'
            ], 404);
        }

        $lastDistrict = District::where('province_code', $province_code)
            ->orderByDesc('district_code')
            ->first();

        if (!$lastDistrict) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) substr($lastDistrict->district_code, strlen($province_code));
            $nextNumber = $lastNumber + 1;
        }

        $districtCode = $province_code . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'district_code' => $districtCode
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'province_code' => ['required', 'exists:provinces,province_code'],
            'district_name_en' => ['required', 'string', 'max:255'],
            'district_name_kh' => ['required', 'string', 'max:255'],
            'district_code' => ['required', 'string', 'max:20', 'unique:districts,district_code'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $provinceCode = $request->province_code;

        if (!str_starts_with($request->district_code, $provinceCode)) {
            return response()->json([
                'message' => 'District code must start with selected province code'
            ], 422);
        }

        $district = District::create([
            'province_code' => $request->province_code,
            'district_name_en' => $request->district_name_en,
            'district_name_kh' => $request->district_name_kh,
            'district_code' => $request->district_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'District created successfully',
            'data' => $district->load('province')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $request->validate([
            'province_code' => ['required', 'exists:provinces,province_code'],
            'district_name_en' => ['required', 'string', 'max:255'],
            'district_name_kh' => ['required', 'string', 'max:255'],
            'district_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('districts', 'district_code')->ignore($district->id),
            ],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $provinceCode = $request->province_code;

        if (!str_starts_with($request->district_code, $provinceCode)) {
            return response()->json([
                'message' => 'District code must start with selected province code'
            ], 422);
        }

        $district->update([
            'province_code' => $request->province_code,
            'district_name_en' => $request->district_name_en,
            'district_name_kh' => $request->district_name_kh,
            'district_code' => $request->district_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'District updated successfully',
            'data' => $district->load('province')
        ]);
    }

    public function destroy($id)
    {
        $district = District::findOrFail($id);
        $district->delete();

        return response()->json([
            'success' => true,
            'message' => 'District deleted successfully'
        ]);
    }
}
