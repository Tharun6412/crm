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
        <div class="table-responsive mt-3 p-2">
            <h4 class="fw-semibold text-decoration-underline">Complaint Status History</h4>
            <table class="table table-bordered table-primary">
                <thead class="table-primary">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Created By</th>
                        <th>Created Date</th>
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

