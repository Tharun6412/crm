@props([
    'ticket' => [],
])
<div class="row g-2 pb-2 my-2 p-2 bg-warning-subtle rounded">
    <div class="col-sm-2 text-end fw-semibold">Ticket Code : </div>
    <div class="col-sm-4">{{ $ticket->code ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Ticket Status : </div>
    <div class="col-sm-4"><x-tickets.status-change :status="$ticket->status" /></div>
    <div class="col-sm-2 text-end fw-semibold">Category : </div>
    <div class="col-sm-4">{{ $ticket->category->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Created By : </div>
    <div class="col-sm-4">{{ $ticket->createdBy->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Created Date: </div>
    <div class="col-sm-4">{{ dateFormat($ticket->created_at) }}</div>
</div>
