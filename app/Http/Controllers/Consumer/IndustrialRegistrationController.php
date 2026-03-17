<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\AwsPath;
use App\Enums\ConnectionType as EnumsConnectionType;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\SegmentType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Http\Requests\Consumer\IndustrialValidationRequest;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerScheme;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\Prepaid;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\ConnectionType;
use App\Models\Master\ConsumerGasRequired;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\FirmType;
use App\Models\Master\FuelType;
use App\Models\Master\Ga;
use App\Models\Master\MasterConsumerScheme;
use App\Models\Master\Segment;
use App\Models\Master\Title;
use App\Notifications\Consumer\RegistrationSmsNotification;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IndustrialRegistrationController extends Controller
{
    /**
     * IPNG consumer registration form
     */
    public function index()
    {
        $geo_areas = Ga::whereIn('id', session()->get('user')['gas'])->get();
        return view('consumers.registration.create-industrial', [
            'geo_areas' => $geo_areas,
            'districts' => [],
            'charge_areas' => [],
            'areas' => [],
            'segments' => Segment::all(),
            'titles' => Title::all(),
            'nominee_relations' => ConsumerNomineeRelation::all(),
            'documents' => DocumentTypes::where('type', 1)->get(),
            'gas_required_list' => ConsumerGasRequired::all(),
            'firm_types' => FirmType::where('type', 1)->get(),
            'fuel_types' => FuelType::all(),
            'schemes' => [],
            'connection_types' => ConnectionType::all(),
        ]);
    }

    /**
     * Store ther Data
    */
    public function store(IndustrialValidationRequest $request)
    {
        // dd($request->all());
        // Data Preparation
        $add_consumer = Consumer::create([
            'segment_id' => SegmentType::INDUSTRIAL->value,
            'connection_type_id' => EnumsConnectionType::POSTPAID->value,
            'title' => $request->title,
            'fname' => $request->name,
            'cof_name' => $request->cof_name,
            'gst' => $request->gst,
            'pan' => $request->pan,
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
            'dcq' => $request->dcq,
            'expected_date' => !empty($request->expected_date) ? Carbon::createFromFormat('d-m-Y', $request->expected_date) : null,
            'distance' => $request->distance,
            'gas_required_id' => $request->gas_required_id,
            'firm_type_id' => $request->firm_type_id,
            'fuel_id' => $request->fuel_type_id,
            'status_id' => EnumsConsumerStatus::PRE_REGISTER->value,
            'created_by' => Auth::id(),
        ]);
        // Temporary CRN Generation
        $crn_code = "TR".$request->geo_area.$request->charge_area.str_pad($add_consumer->id, 5,'0', STR_PAD_LEFT);
        Consumer::where('id', $add_consumer->id)->update(['t_crn' => $crn_code, 'state_id' => $add_consumer->ga->state_id]);
        // Consumer Status History
        ConsumerStatus::create([
            'consumer_id' => $add_consumer->id,
            'status_id' => EnumsConsumerStatus::PRE_REGISTER->value,
            'created_by' => Auth::id(),
        ]);
        // Consumer Scheme Preparation
        $add_consumer_scheme = ConsumerScheme::create([
            'consumer_id' => $add_consumer->id,
            'scheme_id' => NULL,
            'security_deposit' => $request->sd_amount,
            'consumption_deposit' => $request->consumption,
            'total_deposit' => $request->sd_amount + $request->consumption,
            'emi_amount' => 0,
            'rental_amount' => 0,
            'paid_deposit' => 0,
            'balance' => $request->sd_amount + $request->consumption,
            'status' => 0,
        ]);
    
        // Documents Data Preparation
        if($request->has('document_type')) {
            $documents_bulk = DocumentUpload::uploadBulk($request, AwsPath::REGISTRATION->value);
            foreach($request->document_type as $key => $doc_type) {
                $add_consumer_document = ConsumerDocument::create([
                    'consumer_id' => $add_consumer->id,
                    'status_id' => EnumsConsumerStatus::PRE_REGISTER->value,
                    'doc_type_id' => $doc_type,
                    'file_id' => $documents_bulk['file_list'][$key]['file_id'],
                ]);
            }
        }
        // SMS and Email to send
        $sms_response = SmsService::dispatch($add_consumer, new RegistrationSmsNotification(['tcrn' => $crn_code]));
        // Response Message
        return response()->json([
            'success' => 'Consumer Created Successfully with TR number ' . $crn_code . ', click <a href="'.url('consumers').'">here</a> to see all consumers.'
        ]);
    }
} 