@if ($consumers->count() > 0)
    <div class="table-responsive" style="min-height: 400px;">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-success">
                <tr>
                    <th>S.No</th>
                    <th>CRN</th>
                    <th>Name</th>
                    <th>Meter Number</th>
                    <th>Status</th>
                    <th>Added By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consumers as $consumer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $consumer->crn }}</td>
                        <td>{{ $consumer->name }}</td>
                        <td>{{ $consumer->activeMeter?->meter_no }}</td>
                        <td>{{ $consumer->status?->name ?? '-' }}</td>
                        <td>{{ $consumer->createdBy->name }}</td>
                       <td nowrap>
                        <a href="{{ url('consumers/geysers/create/'.$consumer->id) }}" class="btn btn-sm btn-primary ajax-link"> Add Geyser Connection </a> 
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        No Consumer found
    </div>
@endif
@include('scripts.ajax-link',['div' => 'add-geyser'])