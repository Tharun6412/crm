<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Enums\InvoiceItem;
use App\Enums\GeyserStatus;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\TaxType;
use Illuminate\Support\Facades\Auth;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerGeyser;
use App\Models\Consumer\ConsumerGeyserStatus;
use App\Models\Master\BillInvoiceItem;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use App\Exports\Geysers\GeyserExport;
use Maatwebsite\Excel\Facades\Excel;

class GeyserController extends Controller
{
    /**
     * list of geysers
     */
    public function index(Request $request)
    {
        $geysers = ConsumerGeyser::with([
            'consumer:id,crn,fname,lname,ga_id,district_id',
            'invoice:id,invoice_number,status_id,balance_amount,payable_amount,paid_amount,type_id',
        ])
        ->when($request->filled('key'), function($q) use ($request) {
            $q->where('code', 'like', '%' . $request->key . '%');
        })
        ->when($request->has('geo_area'), function ($q) use($request) {
            $q->whereHas('consumer', function($q1) use ($request){
                $q1->whereIn('ga_id', $request->geo_area);
            });
        })
        ->when($request->has('district'), function($q) use ($request){
            $q->whereHas('consumer', function($q1) use ($request){
                $q1->whereIn('district_id', $request->district);
            });
        })
        ->when($request->has('status_id'), function ($q) use ($request) {
            $q->whereHas('invoice', function ($q1) use ($request) {
                $q1->whereIn('status_id', array($request->status_id));
            });
        })
        ->when($request->has('geyser_status'), function ($q) use ($request) {
            $q->whereIn('status_id',$request->geyser_status);
        })
        ->orderByDesc('created_at')
        ->paginate(50)
        ->withQueryString();
        //
        if ($request->ajax())
            return view('consumers.geysers.list-body',['geysers' => $geysers]);
        else
            return view('consumers.geysers.list',['geysers' => $geysers]);
    }
    /**
     * open geyser search modal
     */
    public function createSearch()
    {
        return view('consumers.geysers.search'); 
    }
    /**
     * search consumers for geyser connection
     */
    public function search(Request $request)
    {
        if($request->ajax()){
            if(empty($request->search)) {
                return response()->json(['message' => 'Please enter consumer number'], 422);
            }
        }
             
        $consumers = Consumer::select('id', 'crn', 'fname', 'lname', 'ga_id', 'status_id', 'created_by', 'phone')
        ->when(!(isAdmin() OR isSuperAdmin() OR isFullAccess()), function ($q) {
            $q->whereIn('ga_id', session('user')['gas']);
            })
            ->when($request->filled('search'), function($q) use($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                // Search CRN
                $query->where('crn', 'like', "%{$search}%");
                });
            })
            ->latest()->limit(20)->get();
            //response
            return view('consumers.geysers.search-listbody', ['consumers' => $consumers]);
    }
    /**
     * show geyser create form
     */   
    public function create($id)
    {
        $consumer = Consumer::findOrFail($id);
        $invoice_type = BillInvoiceItem::find(InvoiceItem::GEYSER_CONNECTION_CHARGES->value);
        //response
        return view('consumers.geysers.create',['consumer' => $consumer,'invoice_type' => $invoice_type]);
    }
    /**
     * show geyser details
     */
    public function show($id)
    {
        $geyser = ConsumerGeyser::with(['consumer','invoice'])->findOrFail($id);
        //response
        return view('consumers.geysers.show', ['geyser' => $geyser]);
    }
    /**
     * store new geyser connection
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:255',
        ]);

        $consumer = Consumer::findOrFail($id);
        $invoice_item_type = BillInvoiceItem::find(InvoiceItem::GEYSER_CONNECTION_CHARGES->value);

        $geyser = ConsumerGeyser::create([
            'consumer_id' => $consumer->id,
            'geyser_no' => $request->geyser_no,
            'status_id' => GeyserStatus::REGISTER->value,
            'created_by' => Auth::id(),
        ]);

         // Generate Geyser Code
        $geyser_code = 'G'. str_pad($consumer->district->code, 2, '0', STR_PAD_LEFT). str_pad($geyser->id, 4, '0', STR_PAD_LEFT);
        
        //Inovoice Generate
        $amt = $invoice_item_type->price;
        $gst_calculated_amt = 1.18;
        $base_amt = round($amt / $gst_calculated_amt, 3);
        $tax_amt = round($amt - $base_amt, 3);
        // Invoice Items
        $invoice_items[] = [
            'item_id' => InvoiceItem::GEYSER_CONNECTION_CHARGES->value,
            'quantity' => 1,
            'unit_price' => $base_amt,
            'total_price' => $base_amt,
            'created_at' => now(),
        ];

        // Invoice Header
        $invoice_data = [
            'config' => [
                'state_id' => $consumer->ga->state_id,
                'tax_id' => TaxType::GST->value,
            ],

            'headers' => [
                'type_id' => InvoiceType::GEYSER_CONNECTION->value,
                'consumer_id' => $consumer->id,
                'invoice_date' => now()->toDateString(),

                'base_amount' => $base_amt,
                'taxable_amount' => $base_amt,

                'tax_id' => TaxType::GST->value,
                'tax_value' => 18,
                'tax_amount' => $tax_amt,

                'total_amount' => $amt,
                'payable_amount' => $amt,
                'paid_amount' => 0,
                'balance_amount' => $amt,

                'status_id' => InvoiceStatus::NOT_PAID->value,
                'created_by' => Auth::id(),
            ],

            'items' => $invoice_items,
        ];

        // Create Invoice
        $inv_number = InvoiceService::create($invoice_data);
        // Update Geyser Details 
        $geyser->update([
            'code' => $geyser_code,
            'invoice_id' => $inv_number['invoice_id'],
        ]);
        // Geyser Status History
        ConsumerGeyserStatus::create([
            'geyser_id' => $geyser->id,
            'status_id' => GeyserStatus::REGISTER->value,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        //response
        return response()->json(['success' => 'Geyser created successfully' ]);
    }
    /**
     * Execute Status
     */
    public function execute(Request $request,$id)
    {
        $geyser = ConsumerGeyser::findOrFail($id);
        //response
        return view('consumers.geysers.edit',['geyser' => $geyser, 'status_id' => GeyserStatus::EXECUTE->value,]);
    }
    /**
     * Active Status
     */
    public function active(Request $request,$id)
    {
        $geyser = ConsumerGeyser::findOrFail($id);
        //response
        return view('consumers.geysers.edit',['geyser' => $geyser, 'status_id' => GeyserStatus::ACTIVE->value,]);
    }
    /**
     * Disconnect Status
     */
    public function disconnect(Request $request,$id)
    {
        $geyser = ConsumerGeyser::findOrFail($id);
        //response
        return view('consumers.geysers.edit',['geyser' => $geyser, 'status_id' => GeyserStatus::DISCONNECT->value,]);
    }
    /**
     * Status Change
     */
    public function statusChange(Request $request, $id, $status_id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        $geyser = ConsumerGeyser::find($id);
        // geyser Status Update
        $geyser->update([
            'status_id' => $status_id
        ]);
        // Geyser Status History
        ConsumerGeyserStatus::create([
            'geyser_id' => $geyser->id,
            'status_id' => $status_id,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        //response
        return response()->json(['success' => 'Geyser Status Updated Successfully']);
    }
    /**
     * consumer details relations
     */
    public function consumerGeyser(Request $request,$id)
    {
        $geysers = ConsumerGeyser::where('consumer_id',$id)->orderBy('id', 'desc')->get();
        //response
        return view('consumers.consumers.show-geysers',['geysers' => $geysers]);
    }

    /**
     * Export
     */
    public function geyserExport(Request $request)
    {
        return (new GeyserExport($request))->download('geyser.csv');
    }

}