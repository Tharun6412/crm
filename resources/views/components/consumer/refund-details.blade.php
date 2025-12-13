{{-- Consumer basic details --}}
{{-- @type 0:Nothing, 1:SD Schemes, 2:SD payment, 3:Meter Details --}}

@props([
    'refund' => [],
    'type' => 0,
])
{{-- Consumer basic details --}}
<x-consumer.basic-details :consumer="$refund->consumer" :type="$type" {{ $attributes->merge(['class']) }} />
{{-- Refund details --}}
<div class="row g-2 pb-2 my-2 bg-warning-subtle rounded">
    <div class="col-sm-2 text-end fw-semibold">#Request : </div>
    <div class="col-sm-4">{{ $refund->request_no }}</div>
    <div class="col-sm-2 text-end fw-semibold">Status : </div>
    <div class="col-sm-4">{{ $refund->status->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Requested : </div>
    <div class="col-sm-4">{{ $refund->createdBy->first_name . ' ' . $refund->createdBy->last_name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Date : </div>
    <div class="col-sm-4">{{ $refund->created_at->format('d-m-Y') }}</div>
</div>