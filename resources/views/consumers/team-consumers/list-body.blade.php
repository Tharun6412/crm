<div class="d-flex justify-content-between">
    <div class="d-flex justify-content-between mb-1">
        <div class="row gx-1">
            <div class="col-auto">
                <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
            </div>
            @foreach(request()->except(['key', 'page', 'geo_area', 'charge_area', 'status']) as $name => $value)
                @if(is_array($value))
                    @foreach($value as $item)
                        <input type="hidden" name="{{ $name }}[]" value="{{ $item }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @endif
            @endforeach
            <div class="col-auto">
                <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-auto">
                <a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(request()->except(['key', 'geo_area', 'charge_area'])) }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
            </div>
            <div class="col-auto mt-1 fw-semibold p-1">
               <span>Records Found:</span>&nbsp;{{ $consumers->total() }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-auto">
            <a href="{{ url('consumers/waiting/pending-consumers/exportAssignedConsumers') }}?{{ http_build_query(request()->query())  }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
            </a>
        </div>
    </div>
</div>
{{-- <div id="batch-assign"></div> --}}
<div class="table-responsive mt-2 mb-2">
    <input type="hidden" name="consumer_status" id="consumer_status" value="{{ request()->cns_status[0] }}"/>
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
                @if (in_array(2, request()->status))
                    <th>
                       All<input class="form-check-input border-1 border-primary" type="checkbox" id="check_all"/>
                    </th>
                @endif
                <th>S.No</th>
                <th>CRN</th>
                <th nowrap>Consumer Name</th>
                <th>GA<x-master.ga-filter class="float-end" /></th>
                <th nowrap>Charge Area
                    @if (request()->has('geo_area'))
                        <x-master.charge-area-filter class="float-end"/>
                    @endif
                </th>
                <th>Area
                    @if (request()->has('charge_area'))
                        <x-master.area-filter class="float-end"/>
                    @endif
                </th>
                <th>Sub Area
                    @if (request()->has('area'))
                        <x-master.sub-area-filter class="float-end"/>
                    @endif
                </th>
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
                        @if (is_null($consumer->team_status))
                            <td class="text-center">
                                <input class="form-check-input border-1 border-primary" type="checkbox" name="consumer_ids[]" value="{{ $consumer->id }}"/>
                            </td>
                        @endif
                        <td class="text-center" width="1%">{{ $i++ }}</td>
                        <td>
                            <a href="{{ url('consumers/' . $consumer->id) }}" target="_blank">
                                {{ $consumer->crn ?? $consumer->t_crn}}
                            </a>
                        </td>
                        <td>{{ $consumer->name }}</td>
                        <td nowrap>{{ $consumer->ga->name ?? '' }}</td>
                        <td>{{ $consumer->ca->name ?? '' }}</td>
                        <td>{{ $consumer->area->name ?? ''  }}</td>
                        <td>{{ $consumer->subArea->name ?? '' }}</td>
                        <td>
                            <x-consumer.status :status="$consumer->status" mode='full' />
                        </td>
                        <td class="text-center">{{ $consumer?->ageing_days }}</td>
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
                                <a class="btn btn-outline-primary btn-sm link-modal" href="{{ url('consumers/waiting/pending-consumers/create/'.$consumer->id) }}?{{ http_build_query(request()->query()) }}"><i class="bi bi-person-check-fill"></i>&nbsp;Assign</a>
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
    @if ( request()->has('status') && !in_array(0, request()->status) && !in_array(1, request()->status))
        <div class="mb-3">
            <button type="button" id="bulkAssignBtn" class="btn btn-primary">
                <i class="bi bi-people-fill"></i> Batch Assign
            </button>
        </div>
    @endif
</div>
<div class="p-2">
    <div id="bulkAssignSection" class="card shadow-sm border-0 d-none">
        <div class="card-header bg-info-subtle">
            <strong>Assign Multiple</strong>
        </div>
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <label for="team_id" class="form-label fw-semibold">Select Team :</label>
                    <select name="team_id" id="team_id" class="form-select" onchange="getEmployeesByTeam(this.value)">
                        <option value="">Select Team</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">
                                {{ $team->name }} - {{ $team->departments->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="assign_to" class="form-label fw-semibold">Select Employee :</label>
                    <select name="assign_to" id="assign_to" class="form-select">
                        <option value="">Select Employee</option>
                    </select>
                </div>
            </div>
            <div id="bulk-assign-error"></div>

            <span class="text-danger opacity-75">Note : Select Area Filter to enable teams list</span>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="button" onclick="consumersBatchAssign()" class="btn btn-success">
                        <i class="bi bi-save"></i> Assign Bulk
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div>{{ $consumers->links('utils.paginator',['modDiv' => 'team-consumers-list'])}}</div>
@include('scripts.link-modal')
<script type="text/javascript">
    $(document).on('change', '#check_all', function () {
        $('input[name="consumer_ids[]"]').prop('checked', this.checked);
    });
    // Get Team Employees
    function getEmployeesByTeam(team_id)
    {
        $.get("{{ url('consumers/waiting/pending-consumers/getEmployeesByTeam') }}", {'team_id' : team_id}, function(data) {
            $('#assign_to').empty();
            let options = '<option value = "">Select Employee</option>'
            if(data.users && data.users.length > 0) {
                data.users.forEach(function(user) {
                    options += `<option value="${user.id}">${user.name}</option>`;
                });
            }
            $('#assign_to').html(options);
        });
    }

    // Assign Batch
    function consumersBatchAssign()
    {
        let consumer_ids = [];
        $('input[name="consumer_ids[]"]:checked').each(function () {
            consumer_ids.push($(this).val());
        });

        let team_id = $('#team_id').val();

        if (consumer_ids.length === 0) {
            $('#bulk-assign-error').html('<div class="alert alert-danger mb-0">Please select consumers</div>');
            return;
        }
        $.post("{{ url('consumers/waiting/pending-consumers/consumersBatchAssign') }}", {
            _token: "{{ csrf_token() }}",
            consumer_ids: consumer_ids,
            team_id: team_id,
            status:$('#consumer_status').val(),
            assign_to:$('#assign_to').val(),
        }, function (data) {
            $('#team-consumers-list').html('<div class="alert alert-success">'+data.message+'<br/>Selected Consumers : <span class="fw-bold fs-5">'+data.total+'</span><br/>Successfully Assigned : <span class="fw-bold fs-5">'+data.inserted+'</span></div>');
            // Reload page after 5 seconds
            setTimeout(function () {
                location.reload();
            }, 5000);
        }).fail(function(response){
            $('#bulk-assign-error').html('<div class="alert alert-danger mb-0">' + response.responseJSON.message + '</div>');
        });
    }
</script>