<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->get('per_page', 15);
            $search = trim((string) $request->get('search', ''));

            $provinces = Province::query()
                ->when($search, function ($query) use ($search) {
                    $query->where('name_km', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%")
                        ->orWhere('province_code', 'like', "%{$search}%");
                })
                ->orderByDesc('id')
                ->paginate($perPage)
                ->appends($request->query());

            return response()->json([
                'status' => true,
                'message' => 'Provinces loaded successfully.',
                'data' => $provinces->items(),
                'pagination' => [
                    'current_page' => $provinces->currentPage(),
                    'last_page' => $provinces->lastPage(),
                    'per_page' => $provinces->perPage(),
                    'total' => $provinces->total(),
                    'from' => $provinces->firstItem(),
                    'to' => $provinces->lastItem(),
                    'has_more_pages' => $provinces->hasMorePages(),
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to load provinces',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getProvince()
    {
        try {
            $provinces = Province::orderBy('id', 'desc')->get();

            return response()->json([
                'status' => true,
                'data' => $provinces,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to load provinces',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function nextCode()
    {
        try {
            $nextCode = $this->generateNextProvinceCode();

            return response()->json([
                'status' => true,
                'province_code' => $nextCode,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to generate province code',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'province_name_en' => 'required|string|max:255',
                'province_name_kh' => 'required|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $validated['province_code'] = $this->generateNextProvinceCode();

            $province = Province::create([
                'province_name_en' => $validated['province_name_en'],
                'province_name_kh' => $validated['province_name_kh'],
                'province_code'    => $validated['province_code'],
                'latitude'         => $validated['latitude'] ?? null,
                'longitude'        => $validated['longitude'] ?? null,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Province created successfully',
                'data' => $province,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create province',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $province = Province::findOrFail($id);

            $validated = $request->validate([
                'province_name_en' => 'required|string|max:255',
                'province_name_kh' => 'required|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $province->update([
                'province_name_en' => $validated['province_name_en'],
                'province_name_kh' => $validated['province_name_kh'],
                'latitude'         => $validated['latitude'] ?? null,
                'longitude'        => $validated['longitude'] ?? null,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Province updated successfully',
                'data' => $province,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Province not found',
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update province',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $province = Province::findOrFail($id);
            $province->delete();

            return response()->json([
                'status' => true,
                'message' => 'Province deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Province not found',
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete province',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateNextProvinceCode(): string
    {
        $maxCode = Province::query()
        ->whereNotNull('province_code')
        ->where('province_code', '!=', '')
        ->whereRaw('CAST(province_code AS UNSIGNED) > 0')
        ->max(\DB::raw('CAST(province_code AS UNSIGNED)'));

    $nextNumber = ($maxCode ?? 0) + 1;

    return str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}
