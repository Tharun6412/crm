{{-- Complaint details --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Complaint Details&nbsp;#{{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer + Complaint heads --}}
            <x-consumer.complaint-details :complaint="$complaint" class="bg-info-subtle"/>
            {{-- Complaint details --}}
            <div class="fs-5 px-3 fw-bold">Complaint Details:</div>
            <div class="row g-2">
                <div class="col-sm-2 text-end fw-semibold">Type : </div>
                <div class="col-sm-4">{{ $complaint->type->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Media : </div>
                <div class="col-sm-4">{{ $complaint->media->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Category : </div>
                <div class="col-sm-4">{{ $complaint->category->parent->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Department : </div>
                <div class="col-sm-4">{{ $complaint->category->department->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Sub Category : </div>
                <div class="col-sm-4">{{ $complaint->category->name }}</div>
                <div class="col-sm-2 text-end fw-semibold">Resolution : </div>
                <div class="col-sm-4">
                    {{ $complaint->category->resolution }}&nbsp;{{ ($complaint->category->resolution_type == 1) ? "Days" : "Hours" }}
                </div>
                <div class="col-sm-6"></div>
                <div class="col-sm-2 text-end fw-semibold">Rating : </div>
                <div class="col-sm-4">
                    @if ($complaint->feedback)
                        <x-complaint.rating :rating="$complaint->feedback->rating"/>
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
            
            {{-- Assigned details --}}
            @if ($complaint->assign)
                <div class="row g-2 mt-1">
                    <div class="col-sm-2 text-end fw-semibold">Assigned To : </div>
                    <div class="col-sm-4">{{ $complaint->assign->assigned->first_name }}&nbsp;{{ $complaint->assign->assigned->last_name }}&nbsp;({{ $complaint->assign->assigned->emp_id }})</div>
                    <div class="col-sm-2 text-end fw-semibold">Date : </div>
                    <div class="col-sm-4">{{ $complaint->assign->created_at->format('d-m-Y') }}</div>
                </div>
            @endif

            {{-- Status history --}}
            <div class="table-responsive p-3">
                <h4>Complaint Status History:</h4>
                <table class="table table-bordered table-info mb-0">
                    <thead class="table-info">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Added By</th>
                            <th>Added Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($complaint->statushistory as $status_val)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><x-complaint.status :status="$status_val->status"/></td>
                                <td>{{ $status_val->notes }}</td>
                                <td>{{ $status_val->createdBy->first_name }}&nbsp;{{ $status_val->createdBy->last_name }}</td>
                                <td>{{ $status_val->created_at->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Feedback --}}
            @if ($complaint->feedback)
                <div class="mx-3 p-3 border">
                    <h4 class="text-info text-decoration-underline">Feedback Details:</h4>
                    <div>
                        <span class="fw-semibold"><i class="bi bi-person-heart"></i>&nbsp;{{ $complaint->feedback->collectable->name }}</span>&nbsp;
                        <x-complaint.rating :rating="$complaint->feedback->rating"/>
                    </div>
                    <figure class="ms-3">
                        <blockquote class="blockquote">
                            <p>{{ $complaint->feedback->notes }}</p>
                        </blockquote>
                        <figcaption class="blockquote-footer">
                            {{ $complaint->feedback->created_at?->format('d-m-Y') }} <cite title="Source Title">EMP</cite>
                        </figcaption>
                    </figure>
                </div>
            @endif

            {{-- Comments --}}
            <div class="m-3 border" id="comments">
                @include('complaints.comments')
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>

