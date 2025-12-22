<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "Invoice generated";
    }
    /**
     * Consumer Scheme Accept State
     */
    public function edit(Request $request, $id) 
    {
        $consumer= Consumer::find($id);
        return view('consumers.accept.edit', [
            'consumer' => $consumer, 
            'id' => $id, 
        ]);
    }

    /**
     * Accept/Reject
     * Registered -> Accept/Reject
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
            'status' => 'required',
        ]);
        // Consumer Status History 3= Accept, 9=Reject
        if($request->status == 1) {
            $con_status = 3;
            $status_val = "accepted";
        }else {
            $con_status = 9;
            $status_val = "rejected";
        }
        // Consumer Update
        Consumer::where('id', $id)->update([
            'status_id' => $con_status,
            'updated_by' => Auth::id(),
        ]);
        // Status History
        ConsumerStatus::create([
            'consumer_id' => $id,
            'status_id' => $con_status,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer status updated Successfully!']);
    }
} 