{{-- Geyser Status History --}}
@props([
    'ticket' => [],
])
{{-- Status history --}}
<div class="table-responsive">
            <h4>Ticket Status History:</h4>
            <table class="table table-bordered table-primary mb-0">
                <thead class="table-primary">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Updated By</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($ticket->count()>0)
                        @foreach ($ticket->statusHistory as $status)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><x-tickets.status-change :status="$status->status"/></td>                            
                                <td>{{ $status->notes }}</td>
                                <td>{{ $status->updatedBy?->name }}</td>
                                <td>{{ $status->created_at?->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    @else
                            <tr>
                                <td colspan="6">No Records Found</td>
                            </tr>
                    @endif
                    
                </tbody>
            </table>
        </div>