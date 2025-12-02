<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\ConsumerScheme;
use App\Models\Master\ConsumerSchemeGa;
use App\Models\Master\District;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * Get districts from GA
     * 
     * @param $ga_id
     */
    public function gaDistricts()
    {
        return ('districts');
    }

    /**
     * Get districts, schemes from GA
     * 
     * @param $ga_id
     */
    public function gaDistrictsSchemes(Request $request)
    {
        $districts = District::where('ga_id', $request->ga_id)->get();
        $schemes = ConsumerSchemeGa::with(['scheme'])->where('ga_id', $request->ga_id)->get();

        return response()->json(['districts' => $districts, 'schemes' => $schemes]);
    }

    /**
     * Get charge areas from district
     * 
     * @param $district_id
     */
    public function districtCas(Request $request)
    {
        $charge_areas = Ca::where('district_id', $request->district_id)->get();

        return response()->json(['charge_areas' => $charge_areas]);
    }

    /**
     * Get areas from charge area
     * 
     * @param $ca_id
     */
    public function caAreas(Request $request)
    {
        $areas = Area::where('ca_id', $request->ca_id)->get();

        return response()->json(['areas' => $areas]);
    }

    /**
     * Get Scheme details
     * 
     * @param $scheme_id
     */
    public function schemeDetails (Request $request)
    {
        $scheme_details = ConsumerScheme::find($request->scheme_id);
        
        return response()->json(['scheme_details' => $scheme_details]);
    }
}