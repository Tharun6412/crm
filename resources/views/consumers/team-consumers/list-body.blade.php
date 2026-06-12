<div class="d-flex justify-content-between mb-1">
    <div class="row gx-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
            <input type="hidden" name="cns_status[]" value="{{ request()->cns_status[0] }}" />
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('consumers/waiting/pending-consumers') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-2">
            <span class="fw-semibold">({{ $consumers->total() }})</span> Records found
        </div>
    </div>
</div>
<div class="table-responsive mt-2">
    <table class="table table-bordered table-striped align-middle table-hover">
        <thead class="table-success">
            <tr>
                <th>S.No</th>
                <th>CRN</th>
                <th nowrap>Consumer Name</th>
                <th>GA</th>
                <th nowrap>Charge Area</th>
                <th>Area</th>
                <th>SubArea</th>
                <th width="12%" nowrap>Consumer Status</th>
                <th nowrap>Assigned To</th>
                <th width="11%">Status
                    @php
                        $status_filters = ['' =>'Not Assigned', 0 => 'Assigned', 1 => 'Completed'];
                    @endphp
                    <x-admin.status-filter name="status" :data='$status_filters' class="float-end"/>
                </th>
                <th nowrap>Assign By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($consumers->count()>0)
                @foreach ($consumers as $consumer )
                    <tr>
                        <td class="text-center" width="1%">{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ url('consumers/' . $consumer->id) }}" target="_blank">
                                {{ $consumer->crn }}
                            </a>
                        </td>
                        <td>{{ $consumer->name }}</td>
                        <td>{{ $consumer->ga->name ?? '' }}</td>
                        <td>{{ $consumer->ca->name ?? '' }}</td>
                        <td>{{ $consumer->area->name ?? '' }}</td>
                        <td>{{ $consumer->subarea->name ?? '' }}</td>
                        <td>
                            <x-consumer.status :status="$consumer->status" mode='full' />
                            {{-- {{ $consumer->status->name }} --}}
                        </td>
                        <td>{{ $consumer->teamConsumer->first()?->team?->name ?? '' }}</td>
                        <td>
                            @if($consumer->teamConsumer->first()?->status == '')
                            <span class="badge text-bg-danger"><i class="bi bi-x-lg"></i>&nbsp;Not Assigned</span>
                                
                            @elseif ($consumer->teamConsumer->first()?->status == 0)
                             <span class="badge text-bg-primary"><i class="bi bi-gear"></i>&nbsp;Assigned</span>
                            @elseif ($consumer->teamConsumer->first()?->status == 1)
                             <span class="badge text-bg-success"><i class="bi bi-check"></i>&nbsp;Completed</span>
                            @endif
                        </td>
                        <td>{{ $consumer->teamConsumer->first()?->updatedBy?->name ?? '' }}</td>
                        <td nowrap>
                             @if($consumer->teamConsumer->first()?->status == '')
                                <a class="btn btn-outline-primary btn-sm link-modal" href="{{ url('consumers/waiting/pending-consumers/create/'.$consumer->id) }}"><i class="bi bi-person-check-fill"></i>&nbsp;Assign</a>
                             @endif
                            {{-- <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    @if($consumer->teamConsumer->first()?->status == '')
                                        <li><a class="dropdown-item link-modal" href="{{ url('consumers/waiting/pending-consumers/create/'.$consumer->id) }}"><i class="bi bi-person-check-fill"></i>&nbsp;Assign</a></li>
                                    @endif
                                </ul>
                            </div> --}}
                        </td>
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
@include('scripts.link-modal')