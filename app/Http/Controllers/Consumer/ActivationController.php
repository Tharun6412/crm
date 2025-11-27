<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumersStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivationController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }
    /**
     * TO Get the Deposit Details
     * Consumer Scheme Details
     */
    public function edit(Request $request, $id) 
    {
        return view('consumers.consumers.activated.create', [
            'id' => $id, 
        ]);
    }

    /**
     * Execution State
     * Accepted -> Executed
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
            'status' => 'required',
        ]);
        if($request->status == 1) {
            // 6 = Activation
            Consumer::where('id', $id)->update([
                'status_id' => 6,
                'updated_by' => Auth::id(),
            ]);
            // Status History
            ConsumersStatus::create([
                'consumer_id' => $id,
                'status_id' => 6,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);
            // Response
            return response()->json(['success' => 'Consumer activated Successfully.Go to <a href="'.url('consumers/activated').'">Consumers List</a>']);
        }else {
            return response()->json(['success' => 'Consumer not activated.']);
        }
    }
} 