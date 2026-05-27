<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FindLocationController extends Controller
{
    public function search(Request $request)
    {
        $provinceCode = $request->province_code;
        $districtCode = $request->district_code;
        $communeCode  = $request->commune_code;
        $odCode       = $request->od_code;
        $hcCode       = $request->hc_code;
        $villageCode  = $request->village_code;

        $target = null;
        $level = null;
        $picture = null;

        // Find target for map
        if ($villageCode) {
            $target = DB::table('villages')->where('village_code', $villageCode)->first();
            $level = 'village';
        } elseif ($hcCode) {
            $target = DB::table('hcs')->where('hc_code', $hcCode)->first();
            $level = 'hc';
        } elseif ($odCode) {
            $target = DB::table('ods')->where('od_code', $odCode)->first();
            $level = 'od';
        } elseif ($communeCode) {
            $target = DB::table('communes')->where('commune_code', $communeCode)->first();
            $level = 'commune';
        } elseif ($districtCode) {
            $target = DB::table('districts')->where('district_code', $districtCode)->first();
            $level = 'district';
        } elseif ($provinceCode) {
            $target = DB::table('provinces')->where('province_code', $provinceCode)->first();
            $level = 'province';
        }

        if (!$target) {
            return response()->json([
                'success' => false,
                'message' => 'Location not found',
                'data' => null
            ], 404);
        }

        // Priority image:
        // 1. HC image
        // 2. OD image
        if ($hcCode) {
            $hc = DB::table('hcs')->where('hc_code', $hcCode)->first();

            if ($hc) {
                $rawImage = $hc->image ?? $hc->picture ?? $hc->photo ?? $hc->image_url ?? null;

                if ($rawImage) {
                    // because image stored by ->store('hcs','public') or similar
                    $picture = asset('storage/' . $rawImage);
                }
            }
        }

        if (!$picture && $odCode) {
            $od = DB::table('ods')->where('od_code', $odCode)->first();

            if ($od) {
                $rawImage = $od->image ?? $od->picture ?? $od->photo ?? $od->image_url ?? null;

                if ($rawImage) {
                    // because image stored by ->store('ods','public')
                    $picture = asset('storage/' . $rawImage);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Location found',
            'data' => [
                'level' => $level,
                'picture' => $picture,
                'target' => $target
            ]
        ]);
    }
}