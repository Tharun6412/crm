<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Http\Requests\Consumer\RegistrationValidationRequest;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumersScheme;
use App\Models\Consumer\ConsumersStatus;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\ConsumerGasRequired;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\ConsumerScheme;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use App\Models\Master\Title;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * DPNG consumer registration form
     */
    public function index()
    {
        $geo_areas = Ga::whereIn('id', session()->get('user')['gas'])->get();
        return view('consumers.registration.create-domestic', [
            'geo_areas' => $geo_areas,
            'districts' => [],
            'charge_areas' => [],
            'segments' => Segment::all(),
            'titles' => Title::all(),
            'nominee_relations' => ConsumerNomineeRelation::all(),
            'documents' => DocumentTypes::where('type', 1)->get(),
            'gas_required_list' => ConsumerGasRequired::all(),
            'schemes' => [],
        ]);
    }

    /**
     * Store ther Data
    */
    public function store(RegistrationValidationRequest $request)
    {
        // dd($request->all());
        // Data Preparation
        $add_consumer = Consumer::create([
            'segment_id' => 1,
            'title' => $request->title,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'cof' => $request->cof,
            'cof_name' => $request->cof_name,
            'aadhar' => $request->aadhar,
            'email' => $request->email,
            'phone' => $request->phone,
            'phone_alt' => $request->phone_alt,
            'nominee' => $request->nominee,
            'nominee_relation_id' => $request->nominee_relation_id,
            'hno' => $request->hno,
            'street' => $request->street,
            'colony' => $request->colony,
            'city' => $request->city,
            'ward' => $request->ward,
            'ga_id' => $request->geo_area,
            'district_id' => $request->district,
            'ca_id' => $request->charge_area,
            'pincode' => $request->pincode,
            'lpg_connections' => $request->lpg_connections,
            'dcq' => $request->dcq,
            'expected_date' => !empty($request->expected_date) ? Carbon::createFromFormat('d-m-Y', $request->expected_date) : null,
            'distance' => $request->distance,
            'property_type' => $request->property_type,
            'owner_name' => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'gas_required_id' => $request->gas_required_id,
            'tenant_name' => $request->tenant_name,
            'tenant_phone' => $request->tenant_phone,
            'tenant_email' => $request->tenant_email,
            'gas_required_id' => $request->gas_required_id,
            'status_id' => 1,
            'created_by' => Auth::id(),
        ]);
        // Temporary CRN Generation
        $crn_code = "TR".$request->geo_area.$request->charge_area.str_pad($add_consumer->id, 5,'0', STR_PAD_LEFT);
        Consumer::where('id', $add_consumer->id)->update(['t_crn' => $crn_code]);
        // Consumer Status History
        ConsumersStatus::create([
            'consumer_id' => $add_consumer->id,
            'status_id' => 1,
            'created_by' => Auth::id(),
        ]);
        // Consumer Scheme Preparation
        if($request->has('scheme_id') and !empty($request->scheme_id)) {
            $scheme_details = ConsumerScheme::find($request->scheme_id);
            $add_consumer_scheme = ConsumersScheme::create([
                'consumer_id' => $add_consumer->id,
                'scheme_id' => $scheme_details->id,
                'security_deposit' => $scheme_details->security,
                'consumption_deposit' => $scheme_details->consumption,
                'total_deposit' => $scheme_details->total_deposit,
                'emi_amount' => $scheme_details->emi_amount,
                'rental_amount' => $scheme_details->rental_amount,
                'paid_deposit' => 0,
                'balance' => $scheme_details->security + $scheme_details->consumption,
                'status' => 0,
            ]);
        }
        // Documents Data Preparation
        if($request->has('document_type')) {
            $documents_bulk = DocumentUpload::uploadBulk($request, 'domestic');
            // print "<pre>"; print_r($documents_bulk);exit;
            foreach($request->document_type as $key => $doc_type) {
                $add_consumer_document = ConsumerDocument::create([
                    'consumer_id' => $add_consumer->id,
                    'status_id' => 1,
                    'doc_type_id' => $doc_type,
                    'file_id' => $documents_bulk['file_list'][$key]['file_id'],
                ]);
            }
        }
        // Response Message
        return response()->json([
            'success' => 'Consumer Created Successfully with TR number ' . $crn_code . ', click <a href="'.url('consumers').'">here</a> to see all consumers.'
        ]);
    }
} 