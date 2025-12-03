<?php
namespace APP\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use Symfony\Component\HttpFoundation\Request;

/**
 * This controller is used for the DPNG Gas Bill generation purpose
 * 
 */
class GasInvoiceController extends Controller
{
    /**
     * index 
     */
   public function index() 
   {
        return  "Index Function";
   }
   
   /**
    * Create a Consumer Gas Invoice.
    * @params $id -> Consumer id
    */
   public function create(Request $request, $id)
   {
        $consumer = Consumer::where('status_id', 1)->find($id);
        if($consumer) {
            $invoice = BillInvoice::where('consumer_id', $id)->get();
            print "<pre>"; print_r($invoice); print "</pre>";
            dd($consumer);
            return view('consumers.bills.create', ['consumer' => $consumer, 'invoice' => $invoice]);
        }
        else {
            // Consumer not found or not in active status.
            return "Unable to process: Consumer missing or not in active status.";
        }
   }
}


?>