<?php
namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\AwsPath;
use App\Enums\Constants;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\ReferralStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerScheme;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\ConsumerGasRequired;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\Title;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Http\Requests\Api\Consumer\RegistrationValidationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerData;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\Prepaid;
use App\Models\Consumer\ReferralConsumer;
use App\Models\Master\ConnectionType;
use App\Models\Master\MasterConsumerScheme;
use App\Notifications\Consumer\RegistrationSmsNotification;
use App\Services\ReferralService;
use App\Services\SmsService;

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
            'connection_types' => ConnectionType::all(),
        ], 200);
    }

    /**
     *To Insert consumer
     */
    public function store(RegistrationValidationRequest $request)
    {
        // Insert data
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
            'segment_id' => 1,
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
            'subarea_id' => $request->sub_area,
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
            'tenant_name' => $request->tenant_name,
            'tenant_phone' => $request->tenant_phone,
            'tenant_email' => $request->tenant_email,
            'gas_required_id' => $request->gas_required_id,
            'status_id' => EnumsConsumerStatus::PRE_REGISTER->value,
            'created_by' => Auth::id(),
        ]);
        // Temporary CRN Generation
        $crn_code = "TR".$request->geo_area.$request->charge_area.str_pad($add_consumer->id, 5,'0', STR_PAD_LEFT);
        Consumer::where('id', $add_consumer->id)->update(['t_crn' => $crn_code, 'state_id' => $add_consumer->ga->state_id]);

         // Update the consumer id in the referal request.
        if($referral_id)
        {
            ReferralConsumer::create([
                'request_id'           => $referral_id,
                'status'               => ReferralStatus::PROCESSING->value,
                'referral_consumer_id' => $add_consumer->id,
                'referral_amount' => Constants::REFERRAL_AMOUNT->value,
                'referrer_amount' => Constants::REFERRER_AMOUNT(),
             ]);
        }

        // Consumers Data with GeoCoordinates
        ConsumerData::create([
            'consumer_id' => $add_consumer->id,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'kyc_status' => 0,
            'referrer_consumer_id' => $referrer_id,
        ]);
        // Documents Data Preparation
        if($request->has('document_type')) {
            $documents_bulk = DocumentUpload::uploadBulk($request, AwsPath::REGISTRATION->value);
            foreach($request->document_type as $key => $doc_type) {
                $file_id = $documents_bulk['file_list'][$key]['file_id'] ?? null;
                if (!empty($file_id)) {
                    $add_consumer_document = ConsumerDocument::create([
                        'consumer_id' => $add_consumer->id,
                        'status_id' => EnumsConsumerStatus::PRE_REGISTER->value,
                        'doc_type_id' => $doc_type,
                        'file_id' => $file_id,
                    ]);
                }
            }
        }
        // Consumer Status History
        ConsumerStatus::create([
            'consumer_id' => $add_consumer->id,
            'lat' => $request->lat,
            'lng' => $request->lng,
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
        // IF Connection Type=PREPAID Add record
        if($request->connection_type == 2) {
            Prepaid::create([
                'consumer_id' => $add_consumer->id,
                'bonus' => $scheme_details->bonus,
            ]);
        }
        // Send SMS
        $sms_response = SmsService::dispatch($add_consumer, new RegistrationSmsNotification(['tcrn' => $crn_code]));
        // Rsponse
        return response()->json(['data' => "Consumer created Successfully"], 200);
    }

    /**
     * To Update Sub Area
     */
    public function updateSubArea(Request $request, $con_id)
    {
        // Validation
        $request->validate([
            'area_id' => 'required',
            // 'sub_area' => 'required',
        ]);

        $consumer = Consumer::find($con_id);
        // Update Consumer Details
        $consumer->update([
            'area_id' => $request->area_id,
            'subarea_id' => $request->sub_area,
            'updated_by' => Auth::id(),
        ]);

        // Response
        return response()->json(['success' => 'Consumer details updated Successfully'], 200);
    }
}