<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Hc;
use App\Models\Od;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class HcController extends Controller
{
    public function index()
    {
        $hcs = Hc::with(['province', 'district', 'commune', 'od'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $hcs
        ]);
    }

    public function nextCode($od_code)
    {
        try {
            $od = Od::where('od_code', $od_code)->first();

            if (!$od) {
                return response()->json([
                    'status' => false,
                    'message' => 'OD not found'
                ], 404);
            }

            $latest = Hc::where('od_code', $od_code)
                ->select('hc_code')
                ->orderBy('hc_code', 'desc')
                ->first();

            if (!$latest) {
                $nextNumber = 1;
            } else {
                $lastTwo = substr($latest->hc_code, -2);
                $nextNumber = (int) $lastTwo + 1;
            }

            $newCode = $od_code . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

            return response()->json([
                'status' => true,
                'hc_code' => $newCode
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to generate HC code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'province_code' => ['required', 'exists:provinces,province_code'],
            'district_code' => ['required', 'exists:districts,district_code'],
            'commune_code'  => ['required', 'exists:communes,commune_code'],
            'od_code'       => ['required', 'exists:ods,od_code'],
            'hc_name_en'    => ['required', 'string', 'max:255'],
            'hc_name_kh'    => ['required', 'string', 'max:255'],
            'hc_code'       => ['required', 'string', 'max:50', 'unique:hcs,hc_code'],
            'name_director' => ['nullable', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'latitude'      => ['nullable', 'numeric'],
            'longitude'     => ['nullable', 'numeric'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('hcs', 'public');
        }

        $hc = Hc::create([
            'province_code' => $validated['province_code'],
            'district_code' => $validated['district_code'],
            'commune_code'  => $validated['commune_code'],
            'od_code'       => $validated['od_code'],
            'hc_name_en'    => $validated['hc_name_en'],
            'hc_name_kh'    => $validated['hc_name_kh'],
            'hc_code'       => $validated['hc_code'],
            'name_director' => $validated['name_director'] ?? null,
            'phone'         => $validated['phone'] ?? null,
            'image'         => $imagePath,
            'latitude'      => $validated['latitude'] ?? null,
            'longitude'     => $validated['longitude'] ?? null,
            'visit'         => 0,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Health Center created successfully',
            'data' => $hc->load(['province', 'district', 'commune', 'od'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $hc = Hc::find($id);

        if (!$hc) {
            return response()->json([
                'status' => false,
                'message' => 'Health Center not found'
            ], 404);
        }

        $validated = $request->validate([
            'province_code' => ['required', 'exists:provinces,province_code'],
            'district_code' => ['required', 'exists:districts,district_code'],
            'commune_code'  => ['required', 'exists:communes,commune_code'],
            'od_code'       => ['required', 'exists:ods,od_code'],
            'hc_name_en'    => ['required', 'string', 'max:255'],
            'hc_name_kh'    => ['required', 'string', 'max:255'],
            'hc_code'       => [
                'required',
                'string',
                'max:50',
                Rule::unique('hcs', 'hc_code')->ignore($hc->id),
            ],
            'name_director' => ['nullable', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'latitude'      => ['nullable', 'numeric'],
            'longitude'     => ['nullable', 'numeric'],
        ]);

        $imagePath = $hc->image;

        if ($request->hasFile('image')) {
            if ($hc->image && Storage::disk('public')->exists($hc->image)) {
                Storage::disk('public')->delete($hc->image);
            }

            $imagePath = $request->file('image')->store('hcs', 'public');
        }

        $hc->update([
            'province_code' => $validated['province_code'],
            'district_code' => $validated['district_code'],
            'commune_code'  => $validated['commune_code'],
            'od_code'       => $validated['od_code'],
            'hc_name_en'    => $validated['hc_name_en'],
            'hc_name_kh'    => $validated['hc_name_kh'],
            'hc_code'       => $validated['hc_code'],
            'name_director' => $validated['name_director'] ?? null,
            'phone'         => $validated['phone'] ?? null,
            'image'         => $imagePath,
            'latitude'      => $validated['latitude'] ?? null,
            'longitude'     => $validated['longitude'] ?? null,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Health Center updated successfully',
            'data' => $hc->load(['province', 'district', 'commune', 'od'])
        ]);
    }

    public function destroy($id)
    {
        $hc = Hc::find($id);

        if (!$hc) {
            return response()->json([
                'status' => false,
                'message' => 'Health Center not found'
            ], 404);
        }

        if ($hc->image && Storage::disk('public')->exists($hc->image)) {
            Storage::disk('public')->delete($hc->image);
        }

        $hc->delete();

        return response()->json([
            'status' => true,
            'message' => 'Health Center deleted successfully'
        ]);
    }
}