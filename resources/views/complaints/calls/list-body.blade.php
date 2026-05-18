<div>
    @if ($consumers->count() > 0)
        <div class="table-responsive" style="min-height: 500px;">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-success">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>CRN</th>
                        <th>Connection Type</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Meter Number</th>
                        <th>Status</th>
                        <th>Added By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($consumers as $consumer)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td><a href="{{ url('consumers/'.$consumer->id) }}" target="_blank">{{ $consumer->crn }}</a></td>
                            <td>
                                {{ $consumer->connection_type_id == "1" ? 'POSTPAID' : 'PREPAID' }}
                            </td>
                            <td>{{ $consumer->name }}</td>
                            <td>{{ maskNumber($consumer->phone) }}</td>
                            <td>{{ $consumer->activeMeter?->meter_no }}</td>
                            <td><x-consumer.status :status="$consumer->status" /></td>
                            <td>{{ $consumer->createdBy->name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/create/' . $consumer->id) }}" action="add"><i class="bi bi-chevron-right"></i>&nbsp;Raise Complaint</x-auth.link></li>
                                    </ul>
                                </div>
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
</div>
@include('scripts.link-modal')