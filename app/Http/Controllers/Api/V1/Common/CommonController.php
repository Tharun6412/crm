<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Controller;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\ComplaintCategory;
use App\Models\Master\ConsumerSchemeGa;
use App\Models\Master\District;
use App\Models\Master\MasterConsumerScheme;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * Get districts from GA
     * 
     * @param $ga_id
     */
    public function gaDistricts(Request $request)
    {
        $districts = District::select('id', 'name')->where('ga_id', $request->ga_id)->get();
        return response()->json(['districts' => $districts], 200);
    }
    /**
     * get Schemes By Type
     * @param $type_id, $ga_id
     */
    public function gaSchemesByType(Request $request)
    {
        $schemes = MasterConsumerScheme::select('id', 'name', 'registration', 'security', 'consumption', 'total_deposit', 'min_payment', 'emi_amount', 'rental_amount', 'bonus')->whereHas('schemesGa', function($q) use($request) {
            $q->where('ga_id', $request->ga_id);
        })->where([
            'segment_id' => $request->segment_id,
            'connection_type_id' => $request->type_id,
            'status' => 1,
        ])->get();
        return response()->json(['schemes' => $schemes], 200);
    }
    /**
     * Get districts, schemes from GA
     * 
     * @param $ga_id
     */
    public function gaDistrictsSchemes(Request $request)
    {
        $districts = District::select('id', 'name')->where('ga_id', $request->ga_id)->get();
        $schemes = MasterConsumerScheme::select('id','name', 'registration', 'security', 'consumption', 'total_deposit', 'emi_amount', 'rental_amount')
            ->whereHas('gas', function($q) use($request) {
                $q->where('ga_id', $request->ga_id);
            })->get();
        return response()->json(['districts' => $districts, 'schemes' => $schemes], 200);
    }

    /**
     * Get charge areas from district
     * 
     * @param $district_id
     */
    public function districtCas(Request $request)
    {
        $charge_areas = Ca::where('district_id', $request->district_id)->get();

        return response()->json(['charge_areas' => $charge_areas], 200);
    }

    /**
     * Get areas from charge area
     * 
     * @param $ca_id
     */
    public function caAreas(Request $request)
    {
        $areas = Area::where('ca_id', $request->ca_id)->get();

        return response()->json(['areas' => $areas], 200);
    }

    /**
     * Get Scheme details
     * 
     * @param $scheme_id
     */
    public function schemeDetails (Request $request)
    {
        $scheme_details = MasterConsumerScheme::find($request->scheme_id);

        return response()->json(['scheme_details' => $scheme_details], 200);
    }

    /**
     * Complaint Category
     * Get Sub Category Details By Category
     * @param $category_id
     */
    public function getSubCategories(Request $request)
    {
        $sub_categories = ComplaintCategory::with([
            'type:id,name',
            'priority:id,name',
            'department:id,name',
        ])->select('id', 'name', 'resolution', 'resolution_type', 'type_id', 'department_id', 'priority_id')->where('parent_id', $request->category_id)->get();
        return response()->json(['sub_categories' => $sub_categories]); 
    }
}