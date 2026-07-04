<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\AwsPath;
use App\Enums\Constants;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\ReferralStatus;
use App\Enums\SegmentType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Http\Requests\Consumer\RegistrationValidationRequest;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerData;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerScheme;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\Prepaid;
use App\Models\Consumer\ReferralConsumer;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\ConnectionType;
use App\Models\Master\ConsumerGasRequired;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\Ga;
use App\Models\Master\LpgOmc;
use App\Models\Master\MasterConsumerScheme;
use App\Models\Master\Segment;
use App\Models\Master\Title;
use App\Notifications\Consumer\RegistrationSmsNotification;
use App\Services\ReferralService;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
            'areas' => [],
            'subareas' => [],
            'segments' => Segment::all(),
            'titles' => Title::all(),
            'nominee_relations' => ConsumerNomineeRelation::all(),
            'documents' => DocumentTypes::where('type', 1)->get(),
            'gas_required_list' => ConsumerGasRequired::all(),
            'schemes' => [],
            'connection_types' => ConnectionType::all(),
        ]);
    }

    /**
     * Store ther Data
    */
    public function store(RegistrationValidationRequest $request)
    {
        // dd($request->all());
        $referral_id = 0;
        $referrer_id = NULL;

        // Referral Code Validattion Service.
        if(!empty($request->referral_code))
        {
            $referral = ReferralService::checkValidation($request);
            if(!empty($referral))
            {
                $referral_id = $referral['referral_id'];
                $referrer_id = $referral['referrer_id'];
            }
        }
        // Data Preparation
        $add_consumer = Consumer::create([
            'segment_id' => SegmentType::DOMESTIC->value,
            'connection_type_id' => $request->connection_type,
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
            'subarea_id' => $request->subarea,
            'area_id' => $request->area,
            'ca_id' => $request->charge_area,
            'district_id' => $request->district,
            'ga_id' => $request->geo_area,
            'pincode' => $request->pincode,
            // 'lpg_id' => $request->lpg_id,
            // 'lpg_connections' => $request->lpg_connections,
            'dcq' => $request->dcq,
            'expected_date' => !empty($request->expected_date) ? Carbon::createFromFormat('d-m-Y', $request->expected_date) : null,
            'distance' => $request->distance,
            'property_type' => $request->property_type,
            'owner_name' => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'tenant_name' => $request->tenant_name,
            'tenant_phone' => $request->tenant_phone,
            'tenant_email' => $request->tenant_email,
            'gas_required_id' => $request->gas_required_id,
            'status_id' => EnumsConsumerStatus::PRE_REGISTER->value,
            'created_by' => Auth::id(),
        ]);
        
        // Generate Temporary CRN and update
        $crn_code = 'TR' . $request->geo_area . $request->charge_area . str_pad($add_consumer->id, 5, '0', STR_PAD_LEFT);
        Consumer::where('id', $add_consumer->id)->update(['t_crn' => $crn_code, 'state_id' => $add_consumer->ga->state_id]);

        // Update the consumer id in the referal request.
        if($referral_id)
        {
            ReferralConsumer::create([
                'request_id'           => $referral_id,
                'status'               => ReferralStatus::PROCESSING->value,
                'referral_consumer_id' => $add_consumer->id,
                'referrer_amount' => Constants::REFERRER_AMOUNT(),
                'referral_amount' => Constants::REFERRAL_AMOUNT->value,
            ]);
        }

        // Consumers Data with GeoCoordinates
        ConsumerData::create([
            'consumer_id' => $add_consumer->id,
            'kyc_status' => 0,
            'referrer_consumer_id' => $referrer_id,
        ]);
        // Consumer Status History
        ConsumerStatus::create([
            'consumer_id' => $add_consumer->id,
            'status_id' => EnumsConsumerStatus::PRE_REGISTER->value,
            'created_by' => Auth::id(),
        ]);

        // Consumer Scheme Preparation
        if($request->has('scheme_id') and !empty($request->scheme_id)) {
            $scheme_details = MasterConsumerScheme::find($request->scheme_id);
            $add_consumer_scheme = ConsumerScheme::create([
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
            // Upload document with document package
            $documents_bulk = DocumentUpload::uploadBulk($request, AwsPath::REGISTRATION->value);
            
            // Insert documents
            foreach($request->document_type as $key => $doc_type) {
                $file_id = $documents_bulk['file_list'][$key]['file_id'] ?? null;
                if (!empty($file_id)) {
                    $add_consumer_document = ConsumerDocument::create([
                        'consumer_id' => $add_consumer->id,
                        'status_id' => NULL,
                        'doc_type_id' => $doc_type,
                        'file_id' => $file_id,
                    ]);
                }
            }
        }
        
        // IF Connection Type=PREPAID Add record
        if($request->connection_type == 2) {
            Prepaid::create([
                'consumer_id' => $add_consumer->id,
                'bonus' => $scheme_details->bonus,
            ]);
        }
        
        // Send SMS via Notification
        $sms_response = SmsService::dispatch($add_consumer, new RegistrationSmsNotification(['tcrn' => $crn_code]));
        
        // Response Message
        return response()->json([
            'success' => 'Consumer Created Successfully with TR number ' . $crn_code . ', click <a href="'.url('consumers').'">here</a> to see all consumers.'
        ]);
    }
    //lpg 
    public function lpg($id)
    {
        $omcs = LpgOmc::all();
        $consumer = Consumer::with('consumerData')->findOrFail($id);
        return view('consumers.registration.lpg-edit',['consumer' => $consumer, 'omcs' => $omcs]);
    }
    //lpg update
    public function lpgUpdate(Request $request,$id)
    {
        $request->validate([
            'lpg_id' => 'required|min:10|max:17',
            'lpg_consumer_number' => 'required',
            'lpg_omc_id' => 'required',
            'registered_mobile' => 'required|max:10',
            'lpg_connections' => 'required',
        ]);
        Consumer::findOrFail($id);
        ConsumerData::where('consumer_id', $id)->update([
            'lpg_consumer_number' => $request->lpg_consumer_number,
            'lpg_id' => $request->lpg_id,
            'lpg_omc_id' => $request->lpg_omc_id,
            'registered_mobile' => $request->registered_mobile,
            'lpg_connections' => $request->lpg_connections,
        ]);
        return response()->json(['success' => 'Lpg Updated Successfully']);
    }
} 