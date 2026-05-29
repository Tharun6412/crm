<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-ticket"></i>&nbsp;Tickets&nbsp;-&nbsp;({{ $tickets->count() }})
    </div>
    <div class="p-2">
        @if ($tickets->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Ticket Code</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td ><a href="{{ url('tickets/show/'.$ticket->id) }}" class="link-modal">{{ $ticket->code ?? '' }}</a></td>
                                <td>{{ $ticket->category->name }}</td>
                                <td><x-tickets.status-change :status="$ticket->status"/></td>
                                <td> {{ dateFormat($ticket->created_at) }}</td>
                                <td>{{ $ticket->createdBy->name ?? ''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">
                No records found
            </div>
        @endif
    </div>
</div>
@include('scripts.link-modal')