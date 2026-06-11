{{-- Ticket Listbody --}}
@php
    use \App\Enums\TicketStatus;
@endphp
<div class="d-flex justify-content-between mb-1">
    <div class="row gx-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search.. " value="{{ request()->key }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('tickets/') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-2">
            <span class="fw-semibold">({{ $tickets->total() }})</span>Records Found
        </div>
    </div>
</div>
@php
    $sort_by=(request()->has('sortBy')) ? request()->get('sortBy'):'created_at';
    $sort_order=(request()->has('sortOr')) ? request()->get('sortOr'):'desc';
    $sort_order_inverse=($sort_order=='asc')?'desc':'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
    $i = (($tickets->currentPage() - 1) * $tickets->perPage())+1;
@endphp
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S.No</th>
                <th>Code</th>
                <th>CRN No</th>
                <th>Consumer Name</th>
                <th>Category<x-tickets.category-filter class="float-end"/></th>
                <th>Status<x-tickets.status-filter class="float-end"/></th>
                <th>Created By</th>
                <th nowrap>
                    <a href="{{ $tickets->appends(['sortBy' => 'created_at', 'sortOr' => $sort_order_inverse])->url($tickets->currentPage()) }}">
                        Created Date
                        @if ($sort_by == 'created_at')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a><x-master.date-filter/>
                </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($tickets->count()>0)
                @foreach ($tickets as $ticket)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><a href="{{ url('tickets/show/'.$ticket->id) }}" class="link-modal">{{ $ticket->code }}</a></td>
                        <td><a href="{{ url('consumers/'.$ticket->consumer_id) }}" target="_blank">{{ $ticket->consumer->crn ?? ''}}</a></td>
                        <td>{{ $ticket->consumer->name ?? '' }}</td>
                        <td>{{ $ticket->category->name}}</td>
                        <td><x-tickets.status-change :status="$ticket->status" /></td>
                        <td>{{ $ticket->createdBy->name ?? '' }}</td>
                        <td>{{ dateFormat($ticket->created_at )}}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item link-modal" href={{ url('tickets/show/'.$ticket->id) }}><i class="bi bi-eye"></i>&nbsp;View</a></li>
                                    @switch($ticket->status_id)
                                        @case(\App\Enums\TicketStatus::REGISTER->value)
                                            <li>
                                                <a href="{{ url('tickets/edit/'.$ticket->id) }}" class="dropdown-item link-modal"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
                                            </li>
                                            @if (isAdmin() || isSuperAdmin() || isTicketApproval())
                                                <li>
                                                    <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::APPROVE->value) }}"class="dropdown-item link-modal"><i class="bi bi-patch-check"></i>&nbsp;Approve</a>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::HOLD->value) }}"class="dropdown-item link-modal"><i class="bi bi-pause-circle"></i>&nbsp;Hold</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::CANCEL->value) }}"class="dropdown-item link-modal"><i class="bi bi-ban"></i>&nbsp;Cancel</a>
                                            </li>
                                        @break
                                        @case(\App\Enums\TicketStatus::APPROVE->value)
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::PROCESSING->value) }}"class="dropdown-item link-modal"><i class="bi bi-gear"></i>&nbsp;Processing</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::CLOSE->value) }}"class="dropdown-item link-modal"><i class="bi bi-shield-check"></i>&nbsp;Close</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::HOLD->value) }}"class="dropdown-item link-modal"><i class="bi bi-pause-circle"></i>&nbsp;Hold</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::CANCEL->value) }}"class="dropdown-item link-modal"><i class="bi bi-ban"></i>&nbsp;Cancel</a>
                                            </li>
                                        @break
                                        @case(\App\Enums\TicketStatus::PROCESSING->value)
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::CLOSE->value) }}"class="dropdown-item link-modal"><i class="bi bi-shield-check"></i>&nbsp;Close</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::HOLD->value) }}"class="dropdown-item link-modal"><i class="bi bi-pause-circle"></i>&nbsp;Hold</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::CANCEL->value) }}"class="dropdown-item link-modal"><i class="bi bi-ban"></i>&nbsp;Cancel</a>
                                            </li>
                                        @break
                                        @case(\App\Enums\TicketStatus::HOLD->value)
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::CLOSE->value) }}"class="dropdown-item link-modal"><i class="bi bi-shield-check"></i>&nbsp;Close</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('tickets/statusChange/'.$ticket->id.'/'.\App\Enums\TicketStatus::CANCEL->value) }}"class="dropdown-item link-modal"><i class="bi bi-ban"></i>&nbsp;Cancel</a>
                                            </li>
                                        @break
                                    @endswitch
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10">No Tickets Found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div>
    {{ $tickets->links('utils.paginator',['modDiv' => 'tickets-list']) }}
</div>
@include('scripts.link-modal')