<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Od;
use App\Models\Province;
use App\Models\District;
use App\Models\Commune;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class OdController extends Controller
{
    public function index()
    {
        $ods = Od::with(['province', 'district', 'commune'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $ods
        ]);
    }

    public function nextCode($commune_code)
    {
        $lastOd = Od::where('commune_code', $commune_code)
            ->orderBy('od_code', 'desc')
            ->first();

        if ($lastOd) {
            $lastNumber = (int) substr($lastOd->od_code, -2);
            $nextNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '01';
        }

        $nextCode = $commune_code . $nextNumber;

        return response()->json([
            'success' => true,
            'od_code' => $nextCode
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'province_code'  => ['required', 'exists:provinces,province_code'],
            'district_code'  => ['required', 'exists:districts,district_code'],
            'commune_code'   => ['required', 'exists:communes,commune_code'],
            'od_name_en'     => ['required', 'string', 'max:255'],
            'od_name_kh'     => ['required', 'string', 'max:255'],
            'od_code'        => ['required', 'string', 'max:20', 'unique:ods,od_code'],
            'name_director'  => ['nullable', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'latitude'       => ['nullable', 'numeric'],
            'longitude'      => ['nullable', 'numeric'],
        ]);

        $province = Province::where('province_code', $request->province_code)->first();
        $district = District::where('district_code', $request->district_code)->first();
        $commune = Commune::where('commune_code', $request->commune_code)->first();

        if (!$province || !$district || !$commune) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid province, district, or commune.'
            ], 422);
        }

        if ($district->province_code !== $province->province_code) {
            return response()->json([
                'success' => false,
                'message' => 'District does not belong to selected province.'
            ], 422);
        }

        if ($commune->district_code !== $district->district_code) {
            return response()->json([
                'success' => false,
                'message' => 'Commune does not belong to selected district.'
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ods', 'public');
        }

        $od = Od::create([
            'province_code'  => $request->province_code,
            'district_code'  => $request->district_code,
            'commune_code'   => $request->commune_code,
            'od_name_en'     => $request->od_name_en,
            'od_name_kh'     => $request->od_name_kh,
            'od_code'        => $request->od_code,
            'name_director'  => $request->name_director,
            'phone'          => $request->phone,
            'image'          => $imagePath,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'visit'          => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OD created successfully',
            'data' => $od->load(['province', 'district', 'commune'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $od = Od::findOrFail($id);

        $request->validate([
            'province_code'  => ['required', 'exists:provinces,province_code'],
            'district_code'  => ['required', 'exists:districts,district_code'],
            'commune_code'   => ['required', 'exists:communes,commune_code'],
            'od_name_en'     => ['required', 'string', 'max:255'],
            'od_name_kh'     => ['required', 'string', 'max:255'],
            'od_code'        => [
                'required',
                'string',
                'max:20',
                Rule::unique('ods', 'od_code')->ignore($od->id),
            ],
            'name_director'  => ['nullable', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'latitude'       => ['nullable', 'numeric'],
            'longitude'      => ['nullable', 'numeric'],
        ]);

        $province = Province::where('province_code', $request->province_code)->first();
        $district = District::where('district_code', $request->district_code)->first();
        $commune = Commune::where('commune_code', $request->commune_code)->first();

        if (!$province || !$district || !$commune) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid province, district, or commune.'
            ], 422);
        }

        if ($district->province_code !== $province->province_code) {
            return response()->json([
                'success' => false,
                'message' => 'District does not belong to selected province.'
            ], 422);
        }

        if ($commune->district_code !== $district->district_code) {
            return response()->json([
                'success' => false,
                'message' => 'Commune does not belong to selected district.'
            ], 422);
        }

        $imagePath = $od->image;

        if ($request->hasFile('image')) {
            if ($od->image && Storage::disk('public')->exists($od->image)) {
                Storage::disk('public')->delete($od->image);
            }

            $imagePath = $request->file('image')->store('ods', 'public');
        }

        $od->update([
            'province_code'  => $request->province_code,
            'district_code'  => $request->district_code,
            'commune_code'   => $request->commune_code,
            'od_name_en'     => $request->od_name_en,
            'od_name_kh'     => $request->od_name_kh,
            'od_code'        => $request->od_code,
            'name_director'  => $request->name_director,
            'phone'          => $request->phone,
            'image'          => $imagePath,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OD updated successfully',
            'data' => $od->load(['province', 'district', 'commune'])
        ]);
    }

    public function destroy($id)
    {
        $od = Od::findOrFail($id);

        if ($od->image && Storage::disk('public')->exists($od->image)) {
            Storage::disk('public')->delete($od->image);
        }

        $od->delete();

        return response()->json([
            'success' => true,
            'message' => 'OD deleted successfully'
        ]);
    }
}