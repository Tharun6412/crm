<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumersStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermanentDisconnectController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }
    /**
     * Permanent disconnection form
     */
    public function edit(Request $request, $id) 
    {
        return view('consumers.pd.edit', ['id' => $id]);
    }

    /**
     * Permanent disconnect
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
        ]);
        // 8 = PD
        Consumer::where('id', $id)->update([
            'status_id' => 8,
            'updated_by' => Auth::id(),
        ]);
        // Consumer Status History
        ConsumersStatus::create([
            'consumer_id' => $id,
            'status_id' => 8,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer Permanently Disconnected.Go to <a href="'.url('consumers/pd').'">Consumers List</a>']);
    }
} 