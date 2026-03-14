{{-- Complaint details --}}
@props([
    'complaint' => [],
])
{{-- Consumer basic details --}}
@if($complaint->consumer_id > 0)
    <x-consumer.basic-details :consumer="$complaint->consumer" {{ $attributes->merge(['class']) }} />
@else
    <div class="row g-2 pb-2 mb-2 bg-danger-subtle rounded p-2">
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
<div class="row g-2 pb-2 my-2 bg-warning-subtle rounded p-2">
    <div class="col-sm-2 text-end fw-semibold text-nowrap">Complaint No :</div>
    <div class="col-sm-4">{{ $complaint->code }}</div>
    <div class="col-sm-2 text-end fw-semibold">Status : </div>
    <div class="col-sm-4"><x-complaint.status :status="$complaint->status"/></div>
    <div class="col-sm-2 text-end fw-semibold">Segment : </div>
    <div class="col-sm-4">{{ $complaint->segment->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold text-nowrap">Est. Closed Date : </div>
    <div class="col-sm-4">{{ $complaint->estimated_closed_at?->format('d-m-Y H:i') }} <x-complaint.day-hour-display :complaint="$complaint"/></div>
    <div class="col-sm-2 text-end fw-semibold">Raised Date : </div>
    <div class="col-sm-4">{{ $complaint->created_at->format('d-m-Y') }}</div>
    <div class="col-sm-2 text-end fw-semibold">Closed Date : </div>
    <div class="col-sm-4">{{ $complaint->closed_at?->format('d-m-Y H:i') }}</div>
</div>