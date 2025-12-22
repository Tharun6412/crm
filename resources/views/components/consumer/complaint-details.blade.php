{{-- Complaint details --}}
@props([
    'complaint' => [],
])
{{-- Consumer basic details --}}
<x-consumer.basic-details :consumer="$complaint->consumer" {{ $attributes->merge(['class']) }} />
{{-- Complaint and Category details --}}
<div class="row g-2 pb-2 my-2 bg-warning-subtle rounded">
    <span class="px-2 fw-semibold text-decoration-underline">Complaint Details</span>
    <div class="col-sm-2 text-end fw-semibold text-nowrap">#Complaint No :</div>
    <div class="col-sm-4">{{ $complaint->code }}</div>
    <div class="col-sm-2 text-end fw-semibold">Status : </div>
    <div class="col-sm-4">{{ $complaint->status->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Added By : </div>
    <div class="col-sm-4">{{ $complaint->createdBy->first_name . ' ' . $complaint->createdBy->last_name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Date : </div>
    <div class="col-sm-4">{{ $complaint->created_at->format('d-m-Y') }}</div>
    <div class="col-sm-2 text-end fw-semibold text-nowrap">Est Closed Date : </div>
    <div class="col-sm-4">{{ $complaint->estimated_closed_at->format('d-m-Y') }}</div>
    @if (!empty($complaint->closed_at))
        <div class="col-sm-2 text-end fw-semibold">Closed Date : </div>
        <div class="col-sm-4">{{ $complaint->closed_at->format('d-m-Y') }}</div>
    @endif
    <span class="px-2 fw-semibold text-decoration-underline">Category Details</span>
    <div class="col-sm-2 text-end fw-semibold">Category : </div>
    <div class="col-sm-4">{{ $complaint->category->parent->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Sub Category : </div>
    <div class="col-sm-4">{{ $complaint->category->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Type : </div>
    <div class="col-sm-4">{{ $complaint->type->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Media : </div>
    <div class="col-sm-4">{{ $complaint->media->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Department : </div>
    <div class="col-sm-4">{{ $complaint->category->department->name }}</div>
    <div class="col-sm-2 text-end fw-semibold">Resolution : </div>
    <div class="col-sm-4">{{ $complaint->category->resolution }}&nbsp;{{ ($complaint->category->resolution_type == 1) ? "Days" : "Hours" }}</div>
</div>