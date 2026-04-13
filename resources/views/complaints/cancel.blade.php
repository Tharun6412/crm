{{-- Cancel Complaint --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Cancel Complaint&nbsp;#{{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer + Complaint Details --}}
            <div>
                <x-consumer.complaint-details :complaint="$complaint" class="bg-info-subtle"/>
            </div>
            {{-- Complaint details --}}
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
                <div class="col-sm-2 text-end fw-semibold">Tag : </div>
                <div class="col-sm-4">{{ $complaint->category->tag?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Sub Category : </div>
                <div class="col-sm-4">{{ $complaint->category?->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Resolution : </div>
                <div class="col-sm-4">
                    {{ $complaint->category?->resolution }}&nbsp;{{ ($complaint->category?->resolution_type == 1) ? "Days" : "Hours" }}
                </div>
                {{-- <div class="col-sm-6"></div> --}}
                <div class="col-sm-2 text-end fw-semibold">Rating : </div>
                <div class="col-sm-4">
                    @if ($complaint->feedback)
                        <x-complaint.rating :rating="$complaint?->feedback->rating"/>
                    @endif
                </div>
                <div class="col-sm-2 text-end fw-semibold">Description : </div>
                <div class="col-sm-10">{{ $complaint->description }}</div>
                <div class="col-sm-2 text-end fw-semibold"><i class="bi bi-paperclip"></i>Documents : </div>
                <div class="col-sm-10">
                    @if ($complaint->complaintDocuments->count() > 0)
                        @foreach ($complaint->complaintDocuments as $document)
                            <a href="{{ url('dc/documents/' . $document->file_id) }}" title="{{ $document->file->file_name }}" target="_blank"><i class="bi bi-file-earmark-pdf fs-3"></i></a>        
                        @endforeach
                    @endif
                </div>
            </div>
            <hr/>
            <div id="cancel-success" class="mt-3">
                <h4>Cancel Complaint&nbsp;:</h4>
                <form id="cancel-form" action="{{ url('calls/statusChange/'.$complaint->id.'/'.$status_id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <label class="col-form-label col-sm-3 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="my-1" id="cancel-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-7">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-x-circle" aria-hidden="true">&nbsp;</i>Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'cancel'])