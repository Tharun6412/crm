{{-- User status history --}}
@if ($user->statusHistory->count() > 0)
    <div class="fs-5 fw-semibold">Status History</div>
    <div class="table-responsive">
        <table class="table table-bordered mt-2">
            <thead class="bg-light">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>By</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->statusHistory as $status)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $status->created_at?->format('d.m.Y H:i') }}</td>
                        <td>
                            <x-admin.user-status :status="$status->status" />
                        </td>
                        <td>{{ $status->notes }}</td>
                        <td>{{ $status->createdBy->emp_id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif