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
                <td><span class="fw-semibold">Phone :</span>&nbsp;{{ $complaint?->phone }}</td>
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
                <td><span class="fw-semibold">Mobile Number :&nbsp;{{ $complaint->consumer->phone ?? '' }}</td>
                <td><span class="fw-semibold">Deviation :</span>&nbsp;<x-complaint.day-hour-display :complaint="$complaint"/></td>
            </tr>  
        </table>
    </div>
    <div class="p-2 mb-2 bg-secondary-subtle rounded">
        <div class="fs-5 px-3 fw-bold">Complaint Details:</div>
            <div class="row g-2">
                <div class="col-sm-2 text-end fw-semibold">Type : </div>
                <div class="col-sm-4">{{ $complaint->type?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Media : </div>
                <div class="col-sm-4">{{ $complaint->media?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Category : </div>
                <div class="col-sm-4">{{ $complaint->category?->parent->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Department : </div>
                <div class="col-sm-4">{{ $complaint->category?->department->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">PNGRB Category : </div>
                <div class="col-sm-4">{{ $complaint->category->priority?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">PNGRB Type : </div>
                <div class="col-sm-4">{{ $complaint->category->type?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Sub Category : </div>
                <div class="col-sm-4">{{ $complaint->category?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Resolution : </div>
                <div class="col-sm-4">
                    {{ $complaint->category?->resolution }}&nbsp;{{ ($complaint->category?->resolution_type == 1) ? "Days" : "Hours" }}
                </div>
                <div class="col-sm-2 text-end fw-semibold"><i class="bi bi-paperclip"></i>Documents : </div>
                <div class="col-sm-4">
                    @if ($complaint->complaintDocuments->count() > 0)
                        @foreach ($complaint->complaintDocuments as $document)
                            <a href="{{ url('dc/documents/' . $document->file_id) }}" title="{{ $document->file->file_name }}" target="_blank"><i class="bi bi-file-earmark-pdf fs-3"></i></a>        
                        @endforeach
                    @endif
                </div>
                {{-- <div class="col-sm-6"></div> --}}
                <div class="col-sm-2 text-end fw-semibold">Rating : </div>
                <div class="col-sm-4">
                    @if ($complaint->feedback)
                        <x-complaint.rating :rating="$complaint?->feedback->rating"/>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2 text-end fw-semibold">Description : </div>
                <div class="col-sm-10">{{ $complaint->description }}</div>
            </div>
            {{-- Assigned details --}}
            @if ($complaint->assign)
                <div class="row g-2 mt-1">
                    <div class="col-sm-2 text-end fw-semibold">Assigned To : </div>
                    <div class="col-sm-4">{{ $complaint->assign->assigned?->first_name }}&nbsp;{{ $complaint?->assign->assigned?->last_name }}&nbsp;({{ $complaint->assign->assigned?->emp_id }})</div>
                    <div class="col-sm-2 text-end fw-semibold">Assigned Date : </div>
                    <div class="col-sm-4">{{ $complaint?->assign->created_at?->format('d-m-Y H:i') }}</div>
                </div>
            @endif
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