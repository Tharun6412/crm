{{-- Search form --}}
<div class="table-responsive mt-2">
    <div class="col-auto mt-2 p-1">
        <strong>No. of records</strong>&nbsp;:&nbsp;<span class="fw-semibold">{{ $consumers->total() }}</span>
    </div>
    <table class="table table-bordered table-striped align-middle table-hover">
        <thead class="table-success align-middle">
            <tr>
                <th rowspan="2">S.No</th>
                <th colspan="7" class="bg-success-subtle text-center">Consumer Details</th>
                <th colspan="7" class="bg-primary-subtle text-center">Assigned Data</th>
            </tr>
            <tr>
                <th class="bg-success bg-opacity-50">CRN</th>
                <th class="bg-success bg-opacity-50" width="12%" nowrap>Consumer Status</th>
                <th class="bg-success bg-opacity-50" nowrap>Consumer Name</th>
                <th class="bg-success bg-opacity-50">GA<x-master.ga-filter class="float-end" /></th>
                <th class="bg-success bg-opacity-50" nowrap>
                    <div class="d-flex justify-content-between">
                        <div>Charge Area</div>
                        <div>
                            @if (request()->has('geo_area'))
                                <x-master.charge-area-filter class="float-end"/>
                            @endif
                        </div>
                    </div>
                </th>
                <th class="bg-success bg-opacity-50" nowrap>Area</th>
                <th class="bg-success bg-opacity-50" nowrap>Sub Area</th>
                <th class="bg-primary bg-opacity-50" nowrap>
                    <div class="d-flex justify-content-between">
                        <div>Assigned Date</div>
                        <div>
                            <x-master.date-filter class="float-end"/>
                        </div>
                    </div>
                </th>
                <th class="bg-primary bg-opacity-50" nowrap>Assigned To</th>
                <th class="bg-primary bg-opacity-50" nowrap>Team</th>
                <th class="bg-primary bg-opacity-50" nowrap>Work</th>
                <th class="bg-primary bg-opacity-50" nowrap>Status
                    @php
                        $status_filters = [0 => 'Assigned', 1 => 'Completed'];
                    @endphp
                    <x-admin.status-filter name="status" :data='$status_filters' class="float-end"/>
                </th>
                <th class="bg-primary bg-opacity-50" nowrap>Assign By</th>
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
                        <td>
                            <x-consumer.status :status="$consumer->consumer->status" mode='full' />
                        </td>
                        <td>{{ $consumer->consumer?->name }}</td>
                        <td nowrap>{{ $consumer->consumer->ga->name ?? '' }}</td>
                        <td nowrap>{{ $consumer->consumer->ca->name ?? '' }}</td>
                        <td nowrap>{{ $consumer->consumer->area->name ?? ''  }}</td>
                        <td nowrap>{{ $consumer->consumer->subArea->name ?? '' }}</td>
                        <td nowrap>{{ $consumer->created_at?->format('d-m-Y') }}</td>
                        <td nowrap>{{ $consumer->assignTo?->name }}</td>
                        <td nowrap>{{ $consumer->team->name ?? '' }}</td>
                        <td nowrap>{{ $consumer->assignStatus?->name }}</td>
                        <td nowrap>
                            @if(is_null($consumer->status))
                                <span class="badge text-bg-danger"><i class="bi bi-x-lg"></i>&nbsp;Not Assigned</span>
                            @elseif ($consumer->status == 0)
                                <span class="badge text-bg-primary"><i class="bi bi-gear"></i>&nbsp;Assigned</span>
                            @elseif ($consumer->status == 1)
                                <span class="badge text-bg-success"><i class="bi bi-check"></i>&nbsp;Completed</span>
                            @endif
                        </td>
                        <td>{{ $consumer->createdBy?->name ?? '' }}</td>
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