{{-- Complaint Status History --}}
@props([
    'complaint' => [],
])
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
                    <td>{{ $status_val->createdBy?->first_name }}&nbsp;{{ $status_val->createdBy?->last_name }}</td>
                    <td>{{ $status_val->created_at?->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>           
