{{-- Complaint details --}}
@props([
    'complaint' => [],
])
{{-- Consumer basic details --}}
@if($complaint->consumer_id > 0)
    <x-consumer.basic-details :consumer="$complaint->consumer" {{ $attributes->merge(['class']) }} />
@else
    <div class="row gx-2 pb-2 mb-2 bg-info-subtle">
        <h4 class="fw-semibold text-decoration-underline px-2">Customer Details</h4>
        <div class="col-sm-2 text-end fw-semibold">Name : </div>
        <div class="col-sm-4">{{ $complaint->name }}</div>
        <div class="col-sm-2 text-end fw-semibold">District : </div>
        <div class="col-sm-4">{{ $complaint->district->name }}</div>
        <div class="col-sm-2 text-end fw-semibold">Email : </div>
        <div class="col-sm-4">{{ $complaint->email }}</div>
        <div class="col-sm-2 text-end fw-semibold">GA : </div>
        <div class="col-sm-4">{{ $complaint->ga->name }} ({{ $complaint->ga->code }})</div>
        <div class="col-sm-2 text-end fw-semibold">Phone : </div>
        <div class="col-sm-4">{{ maskNumber($complaint->phone) }}</div>
    </div>
@endif
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
    <div class="col-sm-12"></div>
    <div class="col-sm-2 text-end fw-semibold">Description : </div>
    <div class="col-sm-10">{{ $complaint->description }}</div>
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