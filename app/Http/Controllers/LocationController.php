<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * Get all provinces.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinces(): JsonResponse
    {
        $provinces = Province::select('id', 'name_ar as name', 'code')->orderBy('name_ar')->get();
        return response()->json($provinces);
    }

    /**
     * Get districts by province.
     *
     * @param  int  $provinceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistrictsByProvince(int $provinceId): JsonResponse
    {
        $districts = District::where('province_id', $provinceId)
            ->select('id', 'name_ar as name', 'code')
            ->orderBy('name_ar')
            ->get();
        return response()->json($districts);
    }

    /**
     * Get subdistricts by district.
     *
     * @param  int  $districtId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubdistrictsByDistrict(int $districtId): JsonResponse
    {
        $subdistricts = Subdistrict::where('district_id', $districtId)
            ->select('id', 'name_ar as name', 'code')
            ->orderBy('name_ar')
            ->get();
        return response()->json($subdistricts);
    }

    /**
     * Get villages by subdistrict.
     *
     * @param  int  $subdistrictId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVillagesBySubdistrict(int $subdistrictId): JsonResponse
    {
        $villages = Village::where('subdistrict_id', $subdistrictId)
            ->select('id', 'name_ar as name', 'code')
            ->orderBy('name_ar')
            ->get();
        return response()->json($villages);
    }
}
