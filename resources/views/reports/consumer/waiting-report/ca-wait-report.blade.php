{{-- Employee collection show details --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Connection Progress Report&nbsp;-&nbsp;{{ $ga_name->name ?? "ALL"}}&nbsp;-&nbsp;{{ $status_name ?? "ALL" }}&nbsp;</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="showZeroRows">
                <label class="form-check-label" for="showZeroRows">
                    Show All&nbsp;-&nbsp;({{ $charge_areas->count() }})
                </label>
            </div>
            {{-- Consumer Charge Area status --}}
            <table class="table table-bordered table-striped align-middle" id="waiting-report-table">
                <thead>
                    <tr>
                        <td>S.No</td>
                        <td>Name</td>
                        <td class="text-center">Consumers Waiting</td>
                        <td class="text-center">Responsible Employees</td>
                        <td class="text-center">Available Teams</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ca_count = 0;
                    @endphp
                    @foreach ($charge_areas as $area)
                        <tr class="{{ $area->ca_count == 0 ? 'zero-count d-none' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $area->name }}</td>
                            <td class="text-center">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga_name->id], 'cns_status' => [request()->cns_status], 'charge_area' => [$area->id], 'segments' => [request()->segments], 'connection_type_id' => [request()->connection_type_id]]) }}" target="_blank">
                                    {{ $area->ca_count ?? 0 }}
                                </a>
                                <a type="button" href="{{ url('reports/consumer/waiting/getAreasList') }}?{{ http_build_query(['ga_id'=> $ga_name->id, 'cns_status' => request()->cns_status, 'connection_type_id' => request()->connection_type_id, 'segments' => request()->segments, 'ca_id' => $area->id, 'ca_name' => $area->name]) }}" class="link-canvas float-end" title="Click to view Areas List"><i class="bi bi-arrow-right-square fs-3"></i></a>
                            </td>
                            <td class="text-center">
                                @php
                                    $areaUsers = $users_list_ca[$area->id] ?? [];
                                @endphp
                                @if (count($areaUsers) > 0)
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                            View Employees ({{ count($areaUsers) }})
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach ($areaUsers as $list)
                                                <li class="dropdown-item">
                                                    {{ $list->emp_id }}&nbsp;-&nbsp;{{ $list?->name }}&nbsp;-&nbsp;<small>{{ $list->designation?->name }}</small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-danger btn-sm">No Employees found</button>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $areaTeams = $team_ca[$area->id] ?? [];
                                @endphp
                                @if (count($areaTeams) > 0)
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                            View Teams ({{ count($areaTeams) }})
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach ($areaTeams as $team)
                                                <li class="dropdown-item">
                                                    {{ $team->name }}&nbsp;-&nbsp;<strong>{{ $team->users->count() }}&nbsp;Employees</strong>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-outline-danger btn-sm">No Teams</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td colspan="2" class="text-end">Totals</td>
                    <td class="text-center"><a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga_name->id], 'cns_status' => [request()->cns_status], 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($charge_areas->sum('ca_count')) }}</a></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            
        </div>
        <div class="modal-footer">
            <!-- Export -->
            <button type="button" id="exportBtn" class="btn btn-outline-info"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#showZeroRows').on('change', function () {
        $('.zero-count').toggleClass('d-none', !this.checked);
    });
</script>
@include('scripts.link-canvas')
@include('scripts.export-table', [
    'table' => 'waiting-report-table',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'consumer_waiting_report',
    'sheet'    => 'Report',
])