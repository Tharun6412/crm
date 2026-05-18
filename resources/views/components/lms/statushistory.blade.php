{{-- Geyser Status History --}}
@props([
    'leads' => [],
])
{{-- Status history --}}
<div class="table-responsive">
            <h4>Lead Status History:</h4>
            <table class="table table-bordered table-primary mb-0">
                <thead class="table-primary">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>Status</th>
                        <th>Lead Channel</th>
                        <th>Notes</th>
                        <th>Created By</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads->statushistory as $status)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><x-lms.status-change :status="$status->leadStatus" /></td>                            
                            <td>{{ $status->leadChannel->name ?? ''}}</td>
                            <td>{{ $status->notes }}</td>
                            <td>{{ $status->createdBy?->name }}</td>
                            <td>{{ $status->created_at?->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>