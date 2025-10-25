<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\Spot\ProspectComments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProspectCommentsController extends Controller
{
    // Index Method
    public function index()
    {
        $comments = ProspectComments::orderBy('id', 'desc')->limit(50)->get();
        return view('spot.prospects.comments.list', ['comments' => $comments]);
    }

    /**
     * To insert a Comment
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
     * To delete the Comment By ID
     */
    public function destroy(Request $request, $id)
    {
        // TO Delete the comment
        ProspectComments::where('id', $id)->delete();
    }
}