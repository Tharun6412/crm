{{-- Search form --}}
<div class="table-responsive mt-2">
    <div class="col-auto mt-2">
        <span class="fw-semibold">({{ $consumers->total() }})</span> Records found
    </div>
    <table class="table table-bordered table-striped align-middle table-hover">
        <thead class="table-success">
            <tr>
                <th>S.No</th>
                <th>CRN</th>
                <th nowrap>Consumer Name</th>
                <th>GA<x-master.ga-filter class="float-end" /></th>
                <th nowrap>Charge Area
                    @if (request()->has('geo_area'))
                        <x-master.charge-area-filter class="float-end"/>
                    @endif
                </th>
                <th>Area</th>
                <th>SubArea</th>
                <th width="12%" nowrap>Consumer Status</th>
                <th nowrap>Team</th>
                <th nowrap>Assigned To</th>
                <th nowrap>Assign Work Status</th>
                <th width="11%">Status
                    @php
                        $status_filters = [0 => 'Assigned', 1 => 'Completed'];
                    @endphp
                    <x-admin.status-filter name="status" :data='$status_filters' class="float-end"/>
                </th>
                <th nowrap>Assign By</th>
                <th nowrap>Added Date<x-master.date-filter class="float-end"/></th>
            </tr>
        </thead>
        <tbody>
            @if($consumers->count() > 0)
                @foreach ($consumers as $consumer )
                    <tr>
                        <td class="text-center" width="1%">{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ url('consumers/' . $consumer->consumer_id) }}" target="_blank">
                                {{ $consumer->consumer->crn ?? $consumer->consumer->t_crn}}
                            </a>
                        </td>
                        <td>{{ $consumer->consumer?->name }}</td>
                        <td>{{ $consumer->consumer->ga->name ?? '' }}</td>
                        <td>{{ $consumer->consumer->ca->name ?? '' }}</td>
                        <td>{{ $consumer->consumer->area->name ?? ''  }}</td>
                        <td>{{ $consumer->consumer->subArea->name ?? '' }}</td>
                        <td>
                            <x-consumer.status :status="$consumer->consumer->status" mode='full' />
                        </td>
                        <td>{{ $consumer->team->name ?? '' }}</td>
                        <td>{{ $consumer->assignTo?->name }}</td>
                        <td>{{ $consumer->assignStatus?->name }}</td>
                        <td>
                            @if(is_null($consumer->status))
                                <span class="badge text-bg-danger"><i class="bi bi-x-lg"></i>&nbsp;Not Assigned</span>
                            @elseif ($consumer->status == 0)
                                <span class="badge text-bg-primary"><i class="bi bi-gear"></i>&nbsp;Assigned</span>
                            @elseif ($consumer->status == 1)
                                <span class="badge text-bg-success"><i class="bi bi-check"></i>&nbsp;Completed</span>
                            @endif
                        </td>
                        <td>{{ $consumer->createdBy?->name ?? '' }}</td>
                        <td>{{ $consumer->created_at?->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="15">No Records Found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div>{{ $consumers->links('utils.paginator',['modDiv' => 'team-consumers-list'])}}</div>