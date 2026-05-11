{{-- Geyser Status History --}}
@props([
    'geyser' => [],
])
{{-- Status history --}}
<div class="table-responsive">
            <h4>Geyser Status History:</h4>
            <table class="table table-bordered table-primary mb-0">
                <thead class="table-primary">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Created By</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($geyser->statushistory as $status)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><x-geyser.status-change :status="$status->status"/></td>
                            <td>{{ $status->notes }}</td>
                            <td>{{ $status->createdBy?->name }}</td>
                            <td>{{ $status->created_at?->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>