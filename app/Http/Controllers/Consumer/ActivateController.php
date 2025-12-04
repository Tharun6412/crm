<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumersStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivateController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }
    /**
     * Activate form
     */
    public function edit(Request $request, $id) 
    {
        return view('consumers.activate.edit', ['id' => $id]);
    }

    /**
     * Activate consumer
     * #Activated state
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'notes' => 'required|max:255',
        ]);

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
        return response()->json(['success' => 'Consumer activated successfully!']);
    }
} 