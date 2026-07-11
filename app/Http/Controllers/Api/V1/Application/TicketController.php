<?php
namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Master\TicketCategory;
use App\Models\Tickets\Ticket;
use App\Models\Tickets\TicketStatusHistory;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
     use ApiResponse;
    /**
     * create ticket
     */
    public function create()
    {
        $categories = TicketCategory::with(['departments:id,name'])->select('id','name','department_id')->get();
        return response()->json(['categories' => $categories],200);
    }
    /**
     * store ticket
     */
    public function store(Request $request,$consumer_id)
    {
        $request->validate([
            'category_id' => 'required',
            'description' => 'required',
        ]);
        $ticket = Ticket::create([
            'consumer_id' => $consumer_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'status_id' => TicketStatus::REGISTER->value,
            'created_by' => Auth::id(),
        ]);
        $ticketcode = 'T'.str_pad($ticket->id,5,'0',STR_PAD_LEFT);
        $ticket->update([
            'code' => $ticketcode,
        ]);
        TicketStatusHistory::create([
            'ticket_id' => $ticket->id,
            'status_id' => TicketStatus::REGISTER->value,
            'notes' => $request->description,
            'updated_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Ticket Raised Successfully']);
    }
    /**
     * To display the list of tickets
     */
    public function list(Request $request ,$id)
    {

        $ticket_s = Ticket::with([
            'category:id,name,department_id',
            'category.departments:id,name',
            'status:id,name',
            'createdBy:id,first_name,last_name,mobile',
        ])->select('id','code','category_id','status_id','created_at','created_by')->where('consumer_id',$id)->orderBy('created_at','desc')->paginate(10);
        $tickets = $this->apiPagination($ticket_s);
        return response()->json(['tickets' => $tickets],200);
    }
    /**
     * To show the ticket details
     */
    public function show($id)
    {
        $tickets = Ticket::with([
            'category:id,name,department_id',
            'category.departments:id,name',
            'status:id,name',
            'createdBy:id,first_name,last_name',
            'statusHistory',
            'statusHistory.updatedBy:id,first_name,last_name',
        ])->select(['id','code','category_id','description','status_id','created_at','created_by'])->where('id',$id)->first();
        return response()->json(['tickets' => $tickets],200);
    }
    /**
     * All tickets
     */
    public function index(Request $request)
    {
        $ticket = Ticket::with([
            'consumer:id,crn,fname,lname',
            'category:id,name,department_id',
            'category.departments:id,name',
            'status:id,name',
            'createdBy:id,first_name,last_name,mobile',
        ])
        ->when((!isApiAdmin() && !isApiSuperAdmin() && !isApiFullAccess()), function ($q) {
            $q->whereHas('consumer', function ($consumer) {
                $consumer->whereIn('ga_id', session('user')['gas']);
            });
        })
        ->when($request->filled('key'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->where('code', 'like', "%{$request->key}%")
                    ->orWhereHas('consumer', function ($consumer) use ($request) {
                        $consumer->where('crn', 'like', "%{$request->key}%");
                    });
            });
        })
        ->when($request->has('status'), function ($q) use ($request) {
            $q->whereIn('status_id', $request->status);
        })
        ->when($request->has('category'), function($q) use ($request) {
            $q->whereIn('category_id', $request->category);
        })
        ->when(!empty($request->date_from) && !empty($request->date_to),function($q) use ($request) {
            $q->whereBetween('created_at',[Carbon::createFromFormat('d-m-Y',$request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y',$request->date_to)->endOfDay()->toDateTimeString()]);
        })
        ->when((int) $request->my_tickets === 1, function ($q) {
            $q->where('created_by', Auth::id());
        })
        ->orderByDesc('created_at')->paginate(50);
        $tickets = $this->apiPagination($ticket);

        return response()->json(['tickets' => $tickets],200);
    }

}