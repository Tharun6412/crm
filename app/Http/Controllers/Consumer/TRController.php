<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use Illuminate\Http\Request;

class TRController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 10;
        $query = Consumer::when($request->has('search_key'), function($q) use($request) {
            $q->where(function($q) use($request) {
                $q->where('t_crn', 'like', '%'.$request->get('search_key').'%');
                $q->orWhere('crn', 'like', '%'.$request->get('search_key').'%');
                $q->orWhere('fname', 'like', '%'.$request->get('search_key').'%');
                $q->orWhere('lname', 'like', '%'.$request->get('search_key').'%');
            });
        })->where('status_id', 1);
        $consumers = $query->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        if($request->ajax()) {
            return view('consumers.tr.list-body', [
                'consumers' => $consumers
            ]);
        }else {
            return view('consumers.tr.list', [
                'consumers' => $consumers,
            ]);
        }
    }
    /**
     * sample MEthod
     */
    public function test() 
    {
        echo "test Method";
    }
} 