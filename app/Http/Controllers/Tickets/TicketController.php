<?php

namespace App\Http\Controllers\Tickets;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Master\TicketCategory;
use App\Models\Tickets\Ticket;
use App\Models\Tickets\TicketStatusHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * List of Tickets
     */
    public function index(Request $request)
    {
        $tickets = Ticket::with([
            'consumer:id,crn,fname,lname',
            'category:id,name,department_id',
            'category.departments:id,name',
            'status:id,name',
            'createdBy',
        ])
        ->when((!isAdmin() && !isSuperAdmin() && !isFullAccess()), function ($q) {
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
        ->when($request->has('departments'), function ($q) use ($request) {
            $q->whereHas('category', function ($q1) use ($request) {
                $q1->whereIn('department_id', $request->departments);
            });
        })
        ->when(!empty($request->date_from) && !empty($request->date_to),function($q) use ($request) {
            $q->whereBetween('created_at',[Carbon::createFromFormat('d-m-Y',$request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y',$request->date_to)->endOfDay()->toDateTimeString()]);
        })
        ->when((int) $request->my_tickets === 1, function ($q) {
            $q->where('created_by', Auth::id());
        })
        ->orderByDesc('created_at')->paginate(50)->withQueryString();

        if($request->ajax())
            return view('tickets.tickets.list-body',['tickets' => $tickets]);
        else
            return view('tickets.tickets.list',['tickets' => $tickets]);
    }
    /**
     * create Ticket
     */
    public function create($id)
    {
        $consumer = Consumer::findOrFail($id);
        $categories = TicketCategory::with(['departments'])->get();
        return view('tickets.tickets.create',['categories' => $categories,'consumer' => $consumer]);
    }
    /**
     * Store Ticket
     */
    public function store(Request $request,$id)
    {
        $request->validate([
            'category_id' => 'required',
            'description' => 'required',
        ]);
        $ticket = Ticket::create([
            'consumer_id' => $id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'status_id' => TicketStatus::REGISTER->value,
            'created_by' => Auth::id(),
        ]);
        $ticketCode = 'T'.str_pad($ticket->id,5,'0',STR_PAD_LEFT);
        $ticket->update([
            'code' => $ticketCode,
        ]);
        TicketStatusHistory::create([
            'ticket_id' => $ticket->id,
            'status_id' => TicketStatus::REGISTER->value,
            'updated_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Ticket Created Successfully. Ticket Code : '.$ticketCode,]);
    }
    /**
     * Edit Ticket
     */
    public function edit($id)
    {
        $ticket = Ticket::with(['consumer'])->findOrFail($id);
        $categories = TicketCategory::all();
        return view('tickets.tickets.edit',['ticket' => $ticket,'categories' => $categories]);
    }
    /**
     * Update Ticket
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'category_id' => 'required',
            'description' => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);
        //response
        return response()->json(['success' => 'Ticket Details Updated Successfully']);
    }
    /**
     * Status Change
     */
    public function statusChange($id,$status_id)
    {
        $ticket = Ticket::findOrFail($id);
        $status = $status_id;
        return view('tickets.tickets.status',['ticket' => $ticket,'status' => $status]);
    }
    /**
     * Status Update
     */
    public function statusUpdate(Request $request,$id,$status_id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'status_id' => $status_id,
        ]);
        //Status Update
        $ticket = TicketStatusHistory::create([
            'ticket_id' => $ticket->id,
            'status_id' => $status_id,
            'notes' => $request->notes,
            'updated_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Status Updated Successfully']);
    }
    /**
     * Show Tickets
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.tickets.show',['ticket' => $ticket]);
    }
    /**
     * Consumer Ticket
     */
    public function consumerTicket($id)
    {
        $tickets=Ticket::where('consumer_id',$id)->orderBy('id','desc')->get();
        return view('consumers.consumers.show-tickets',['tickets' => $tickets]);
    }
}
