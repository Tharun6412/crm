<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Consumer\RegistrationValidationRequest;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumersScheme;
use App\Models\Consumer\ConsumersStatus;
use App\Models\DocumentCentre\DocumentTypes;
use App\Models\Master\Ca;
use App\Models\Master\ConsumerGasRequired;
use App\Models\Master\ConsumerNomineeRelation;
use App\Models\Master\ConsumerScheme;
use App\Models\Master\ConsumerSchemeGa;
use App\Models\Master\District;
use App\Models\Master\Ga;
use App\Models\Master\PaymentType;
use App\Models\Master\Segment;
use App\Models\Master\Title;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * Index method
     */
    public function index()
    {
        echo "Index Method";
    }

    public function create()
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
     * Get Districts By GA
     */
    public function getDistrictsByGa(Request $request)
    {
        $districts = District::where('ga_id', $request->ga_id)->get();
        return response()->json(['districts' => $districts]);
    }

    /**
     * Get Districts By GA
     */
    public function getCasByDistrict(Request $request)
    {
        $charge_areas = Ca::where('district_id', $request->district_id)->get();
        return response()->json(['charge_areas' => $charge_areas]);
    }
    /**
     * Store ther Data
     */
    public function store(RegistrationValidationRequest $request)
    {
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
            'expected_date' => Carbon::createFromFormat('d-m-Y', $request->expected_date),
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
        $crn_code = "T".$request->geo_area.$request->district.str_pad($add_consumer->id, 5,'0', STR_PAD_LEFT);
        Consumer::where('id', $add_consumer->id)->update(['t_crn' => $crn_code]);
        // Consumer Status History
        ConsumersStatus::create([
            'consumer_id' => $add_consumer->id,
            'status_id' => 1,
            'created_by' => Auth::id(),
        ]);
        // Documents Data Preparation
        $add_consumer_document = ConsumerDocument::create([
            'consumer_id' => $add_consumer->id,
            'status_id' => 1,
            'doc_type_id' => 1,
        ]);
        // Consumer Scheme Preparation
        $add_consumer_scheme = ConsumersScheme::create([
            'consumer_id' => $add_consumer->id,
            'scheme_id' => 1,
            'security_deposit' => 1000,
            'consumption_deposit' => 500,
            'total_deposit' => 1500,
            'emi_amount' => null,
            'paid_deposit' => 1500,
            'balance' => 0,
            'status' => 1,
        ]);
        // Response Message
        return response()->json(['success' => 'Consumer Created Successfully']);
    }

    /**
     * To view Deposit Details
     */
    public function getConsumerDepositDetails(Request $request, $id)
    {
        $consumer = Consumer::find($id);
        $payment_types = PaymentType::all();
        $consumer_scheme = ConsumersScheme::with(['scheme'])->where('consumer_id', $id)->first();
        return view('consumers.deposit-details.pay', [
            'consumer' => $consumer,
            'consumer_scheme' => $consumer_scheme,
            'payment_types' => $payment_types,
        ]);
    }
    /**
     * After Pay Deposit
     * @var char Consumer Number Generated
     */
    public function payDeposit(Request $request, $id)
    {
        $consumer = Consumer::where('id', $id)->get();
        // Scheme Payment Updated
        ConsumersScheme::where('consumer_id', $id)->update([
            'paid_deposit' => 1
        ]);
    }
} 