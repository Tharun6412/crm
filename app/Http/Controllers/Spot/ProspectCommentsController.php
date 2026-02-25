<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\Spot\ProspectComments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProspectCommentsController extends Controller
{
    /**
     * Display the List of comments of all prospects and limited to 50
     * 
     * This Method:
     * - Fetch the ProspectComments details
     * - Latest 50 records to be displayed
     * 
     * @return view
     */
    public function index()
    {
        $comments = ProspectComments::orderBy('id', 'desc')->limit(50)->get();
        return view('spot.prospects.comments.list', ['comments' => $comments]);
    }

    /**
     * Add/Insert the comment based on Prospect
     * 
     * This Method:
     * - Validates the required field (notes)
     * - creates a record in the ProspectComments table.
     * 
     * @param int $prospect_id
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'comments' => 'required|max:600',
        ]);
        // To insert the Comments 
        ProspectComments::create([
            'prospect_id' => $id,
            'comments' => $request->comments,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Deletes the selected Comment from the ProspectComments table.
     */
    public function destroy(Request $request, $id)
    {
        // TO Delete the comment
        ProspectComments::where('id', $id)->delete();
    }
}