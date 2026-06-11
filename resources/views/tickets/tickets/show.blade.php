{{-- Ticket show --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Ticket Details : {{ $ticket->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-expanded="true"></button>
        </div>
        <div class="modal-body">
            <div>
                <x-consumer.basic-details :consumer="$ticket->consumer" class="bg-info-subtle"/>
            </div>
            <div>
                <x-tickets.ticket-details :ticket="$ticket"/>
            </div>
            <div class="p-3">
                <h4>Description : </h4> 
                <div class="border rounded p-3">
                    {{ $ticket->description }}
                </div>
            </div>
            <div>
                <x-tickets.statushistory :ticket="$ticket"/>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>