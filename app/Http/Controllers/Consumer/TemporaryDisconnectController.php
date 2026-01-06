<?php
namespace App\Http\Controllers\Consumer;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemporaryDisconnectController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }
    /**
     * Temporary Disconnection form
     */
    public function edit(Request $request, $id) 
    {
        return view('consumers.td.edit', ['id' => $id]);
    }

    /**
     * Temporary disconnect
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
        ]);
        // 7 = TD
        Consumer::where('id', $id)->update([
            'status_id' => EnumsConsumerStatus::TD->value,
            'updated_by' => Auth::id(),
        ]);
        // Consumer Status History
        ConsumerStatus::create([
            'consumer_id' => $id,
            'status_id' => EnumsConsumerStatus::TD->value,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer Temporarily Disconnected.Go to <a href="'.url('consumers/td').'">Consumers List</a>']);
    }
} 