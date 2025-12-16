<?php
namespace App\Http\Controllers\Api\V1\Application;

use App\Http\Controllers\Controller;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumersScheme;
use App\Models\Consumer\ConsumersStatus;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\ConsumerGasRequired;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\ConsumerScheme;
use App\Models\Master\Title;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Http\Requests\Api\Consumer\RegistrationValidationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Consumer\Consumer;

/**
 * Consumer Registration Controller
 */
class ConsumerRegistrationController extends Controller
{
    /**
     * DropDown Lists to create consumer
     */
    public function create(Request $request)
    {
        // Get the dropdown list
        return response()->json([
            'titles' => Title::select('id', 'name')->where('type', 1)->get(),
            'relation_titles' => Title::select('id', 'name')->where('type', 2)->get(),
            'nominee_relations' => ConsumerNomineeRelation::select('id', 'name')->get(),
            'documents' => DocumentTypes::select('id', 'name')->where('type', 1)->get(),
            'gas_required_list' => ConsumerGasRequired::select('id', 'name')->get(),
        ], 200);
    }

    /**
     *To Insert consumer
     */
    public function store(RegistrationValidationRequest $request)
    {
        // Insert data
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
            'area_id' => $request->area,
            'ca_id' => $request->charge_area,
            'district_id' => $request->district,
            'ga_id' => $request->geo_area,
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
        // Documents Data Preparation
        if($request->has('document_type')) {
            $documents_bulk = DocumentUpload::uploadBulk($request, 'domestic');
            foreach($request->document_type as $key => $doc_type) {
                $add_consumer_document = ConsumerDocument::create([
                    'consumer_id' => $add_consumer->id,
                    'status_id' => 1,
                    'doc_type_id' => $doc_type,
                    'file_id' => $documents_bulk['file_list'][$key]['file_id'],
                ]);
            }
        }
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
        // Send SMS
        // Rsponse
        return response()->json(['data' => "Consumer created Successfully"], 200);
    }
}