{{-- Complaint details --}}
@props([
    'complaint' => [],
])
{{-- Consumer basic details --}}
@if($complaint->consumer_id > 0)
    <x-consumer.basic-details :consumer="$complaint->consumer" {{ $attributes->merge(['class']) }} />
@else
    <div class="p-2 mb-2 bg-info-subtle rounded">
        <table class="table table-borderless table-sm">
            <tr>
                <td><span class="fw-semibold">Name :</span>&nbsp;{{ $complaint->name }}</td>
                <td><span class="fw-semibold">District :</span>&nbsp;{{ $complaint->district->name }}</td>
            </tr>
            <tr>
                <td><span class="fw-semibold">Email :</span>&nbsp;{{ $complaint->email }}</td>
                <td><span class="fw-semibold">GA :</span>&nbsp;{{ $complaint->ga->name }} ({{ $complaint->ga->code }})</td>
            </tr>
            <tr>
                <td><span class="fw-semibold">Phone :</span>&nbsp;{{ maskNumber($complaint->phone) }}</td>
            </tr>  
        </table>
    </div>
@endif
{{-- Complaint and Category details --}}
    <div class="p-2 mb-2 bg-warning-subtle rounded">
        <table class="table table-borderless table-sm">
            <tr>
                <td><span class="fw-semibold">Complaint No :</span>&nbsp;{{ $complaint->code }}</td>
                <td><span class="fw-semibold">Status :</span>&nbsp;<x-complaint.status :status="$complaint->status"/></td>
            </tr>
            <tr>
                <td><span class="fw-semibold">Segment :</span>&nbsp;{{ $complaint->segment->name ?? '' }}</td>
                <td><span class="fw-semibold">Est. Closed Date :</span>&nbsp;{{ $complaint->estimated_closed_at?->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td><span class="fw-semibold">Raised Date :</span>&nbsp;{{ $complaint->created_at->format('d-m-Y') }}</td>
                <td><span class="fw-semibold">Closed Date :</span>&nbsp;{{ $complaint->closed_at?->format('d-m-Y H:i') }}</td>
            </tr> 
             <tr>
                <td><span class="fw-semibold">&nbsp;</td>
                <td><span class="fw-semibold">Deviation :</span>&nbsp;<x-complaint.day-hour-display :complaint="$complaint"/></td>
            </tr>  
        </table>
    </div>
{{-- <div class="row g-2 pb-2 my-2 bg-warning-subtle rounded p-2">
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
</div> --}}