<div class="table-responsive mt-2">
    <div class="col-auto mt-2">
        <span class="fw-semibold">({{ $consumers->total() }})</span> Records found
    </div>
    @php
        $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'cns_consumers.created_at';
        $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
        $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
        $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
        $i = (($consumers->currentPage() - 1) * $consumers->perPage())+1;
    @endphp
    <table class="table table-bordered table-striped align-middle table-hover page-sort">
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
                <th>
                    <a href="{{ $consumers->appends(['sortBy' => 'ageing_days','sortOr' => $sort_order_inverse])->url($consumers->currentPage()) }}">
                        Days
                        @if ($sort_by == 'ageing_days')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th>Team</th>
                <th nowrap>Assigned To</th>
                <th nowrap>Assign Work Status</th>
                <th width="11%">Status
                    @php
                        $status_filters = [2 =>'Not Assigned', 0 => 'Assigned', 1 => 'Completed'];
                    @endphp
                    <x-admin.status-filter name="status" :data='$status_filters' class="float-end"/>
                </th>
                <th nowrap>Assign By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($consumers->count() > 0)
                @foreach ($consumers as $consumer )
                    <tr>
                        <td class="text-center" width="1%">{{ $i++ }}</td>
                        <td>
                            <a href="{{ url('consumers/' . $consumer->id) }}" target="_blank">
                                {{ $consumer->crn ?? $consumer->t_crn}}
                            </a>
                        </td>
                        <td>{{ $consumer->name }}</td>
                        <td>{{ $consumer->ga->name ?? '' }}</td>
                        <td>{{ $consumer->ca->name ?? '' }}</td>
                        <td>{{ $consumer->area->name ?? ''  }}</td>
                        <td>{{ $consumer->subArea->name ?? '' }}</td>
                        <td>
                            <x-consumer.status :status="$consumer->status" mode='full' />
                        </td>
                        <td>{{ $consumer?->ageing_days }}</td>
                        <td>{{ $consumer->team_name ?? '' }}</td>
                        <td>{{ $consumer->assign_name ?? '' }}</td>
                        <td>{{ $consumer->status_name ?? '' }}</td>
                        <td>
                            @if(is_null($consumer->team_status))
                                <span class="badge text-bg-danger"><i class="bi bi-x-lg"></i>&nbsp;Not Assigned</span>
                            @elseif ($consumer->team_status == 0)
                                <span class="badge text-bg-primary"><i class="bi bi-gear"></i>&nbsp;Assigned</span>
                            @elseif ($consumer->team_status == 1)
                                <span class="badge text-bg-success"><i class="bi bi-check"></i>&nbsp;Completed</span>
                            @endif
                        </td>
                        <td>{{ $consumer->team_created_by ?? '' }}</td>
                        <td nowrap>
                            @if(is_null($consumer->team_status))
                                <a class="btn btn-outline-primary btn-sm link-modal" href="{{ url('consumers/waiting/pending-consumers/create/'.$consumer->id) }}"><i class="bi bi-person-check-fill"></i>&nbsp;Assign</a>
                            @else
                                @if ($consumer->assign_to == auth()->id())
                                    @if ($consumer->team_status == 0 AND $consumer->team_status_id == \App\Enums\ConsumerStatus::REGISTER->value)
                                        <x-auth.link class="btn btn-primary btn-sm link-modal" href="{{ url('consumers/trPayment/' . $consumer->id . '/edit') }}" action="pdpst"></i>&nbsp;Pay Deposit</x-auth.link>
                                    @endif
                                    @if ($consumer->team_status == 0 AND $consumer->team_status_id == \App\Enums\ConsumerStatus::ACCEPT->value)
                                        <x-auth.link class="btn btn-info btn-sm link-modal" href="{{ url('consumers/accept/'.$consumer->id.'/edit') }}" action="acpt"></i>&nbsp;Accept</x-auth.link>
                                    @endif
                                    @if ($consumer->team_status == 0 AND $consumer->team_status_id == \App\Enums\ConsumerStatus::EXECUTE->value)
                                        <x-auth.link class="btn btn-secondary btn-sm link-modal" href="{{ url('consumers/execute/'.$consumer->id.'/edit') }}" action="exect"></i>&nbsp;Execute</x-auth.link>
                                    @endif
                                    @if ($consumer->team_status == 0 AND $consumer->team_status_id == \App\Enums\ConsumerStatus::HSC->value)
                                        <x-auth.link class="btn btn-warning btn-sm link-modal" href="{{ url('consumers/hsconnect/'.$consumer->id.'/edit') }}" action="hsc"></i>&nbsp;HSC</x-auth.link>
                                    @endif
                                    @if ($consumer->team_status == 0 AND $consumer->team_status_id == \App\Enums\ConsumerStatus::ACTIVATE->value)
                                        <x-auth.link class="btn btn-success btn-sm link-modal" href="{{ url('consumers/activate/'.$consumer->id.'/edit') }}" action="actvt"></i>&nbsp;Activate</x-auth.link>
                                    @endif
                                @endif
                            @endif
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