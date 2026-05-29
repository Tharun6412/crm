{{-- Ticket Status --}}
@php
    $statusName = \App\Enums\TicketStatus::from($status)->name;
@endphp
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Manage Status : {{ $ticket->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="status-success">
                <form id="status-form" action="{{ url('tickets/statusUpdate/'.$ticket->id.'/'.$status) }}" method="POST">
                    @csrf
                    <x-tickets.ticket-details :ticket="$ticket" />
                    <h5>Update Status</h5>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-2 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <small class="text-muted">Maximum 225 Characters Allowed</small>
                        </div>
                    </div>
                    <div id="status-error" class="mb-3"></div>
                    <div class="text-center">
                        <button type="submit"class="btn btn-success"><i class="bi bi-check-circle"></i>Update Status</button>
                    </div>
                </form>
                <x-tickets.statushistory :ticket="$ticket"/>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit',['form' => 'status'])