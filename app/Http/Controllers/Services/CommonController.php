<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\ConsumerSchemeGa;
use App\Models\Master\District;
use App\Models\Master\Ga;
use App\Models\Master\MasterConsumerScheme;
use App\Models\Master\SubArea;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * Get GeoAreas from state
     * @param $state_id
     */
    public function stateGas(Request $request)
    {
        $geo_areas = Ga::select('id', 'name')->where('state_id', $request->state_id)->get();
        return response()->json(['geo_areas' => $geo_areas]);
    }
    /**
     * Get districts from GA
     * 
     * @param $ga_id
     */
    public function gaDistricts(Request $request)
    {
        $districts = District::select('id', 'name')->where('ga_id', $request->ga_id)->get();

        return response()->json(['districts' => $districts]);
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
     * Get Schemes By Type
     * @param $type_id, $ga_id
     */
    public function gaSchemesByType(Request $request)
    {
        $schemes = ConsumerSchemeGa::with(['scheme'])->whereHas('scheme', function($q) use($request) {
            $q->where([
                'segment_id' => $request->segment_id,
                'connection_type_id' => $request->type_id,
                'status' => 1,
                'conversion_scheme' => 0,
            ]);
        })->where('ga_id', $request->ga_id)->get();
        return response()->json(['schemes' => $schemes]);
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
     * Get subarea from area
     */
    public function areaSubareas(Request $request)
    {
        $subareas = SubArea::where('area_id',$request->area_id)->get();
        return response()->json(['subareas' => $subareas]);
    }

    /**
     * Get Scheme details
     * 
     * @param $scheme_id
     */
    public function schemeDetails (Request $request)
    {
        $scheme_details = MasterConsumerScheme::find($request->scheme_id);
        
        return response()->json(['scheme_details' => $scheme_details]);
    }
}