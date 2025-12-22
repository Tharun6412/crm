<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">View Complaint Details&nbsp;#{{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer + Complaint Details --}}
            <x-consumer.complaint-details :complaint="$complaint" class="bg-info-subtle"/>
        </div>
        <div class="p-3">
            <h4 class="fw-semibold text-decoration-underline">Assigned Details</h4>
            @if ($complaint->assign)
                <div class="row g-2">
                    <div class="col-sm-2 text-end fw-semibold">Assigned To : </div>
                    <div class="col-sm-4">{{ $complaint->assign->assigned->first_name }}&nbsp;{{ $complaint->assign->assigned->last_name }}&nbsp;({{ $complaint->assign->assigned->emp_id }})</div>
                    <div class="col-sm-2 text-end fw-semibold">Assigned Date : </div>
                    <div class="col-sm-4">{{ $complaint->assign->created_at->format('d-m-Y') }}</div>
                </div>
            @else
                <div class="alert alert-warning">Complaint not assigned.</div>
            @endif
        </div>
        <div class="p-3">
            <h4 class="fw-semibold text-decoration-underline">Documents</h4>
            @if ($complaint->complaintDocuments->count() > 0)
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Document</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($complaint->complaintDocuments as $document)
                            <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                <a href="{{ url('dc/documents/'.$document->file_id) }}" title="{{ $document->file->file_name }}" target="_blank"><i class="bi bi-file-earmark-pdf fs-5 text-danger"></i></a>
                            </td>
                            <td>{{ $document->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">
                    <span>No Documents found</span>
                </div>
            @endif
            <table></table>
        </div>
        <div class="table-responsive p-3">
            <h4 class="fw-semibold text-decoration-underline">Complaint Status History</h4>
            <table class="table table-bordered table-primary">
                <thead class="table-primary">
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
                            <td>{{ $status_val->status->name }}</td>
                            <td>{{ $status_val->notes }}</td>
                            <td>{{ $status_val->createdBy->first_name }}&nbsp;{{ $status_val->createdBy->last_name }}</td>
                            <td>{{ $status_val->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>

