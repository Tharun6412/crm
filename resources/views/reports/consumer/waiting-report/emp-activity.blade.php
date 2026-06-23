<div class="d-flex flex-row justify-content-between pb-3">
    <div>&nbsp;</div>
    <div>
        @if ($users->count() > 0)
            <button type="button" id="exportBtn2" class="btn btn-outline-info btn-md"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
        @endif
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered align-middle table-striped table-hover" id="ewcp-table">
        <thead class="table-success align-middle">
            <tr>
                <th rowspan="2">S.No</th>
                <th rowspan="2" nowrap>Employee ID</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Department</th>
                <th rowspan="2">Role(s)</th>
                <th rowspan="2">Geo Area</th>
                <th rowspan="2">Charge Areas</th>
                <th colspan="{{ $statuses->count() }}" class="text-center">Consumers Progress</th>
            </tr>
            <tr>
                @foreach ($statuses as $status)
                    <th class="bg-success bg-opacity-75 text-end">{{ $status->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if ($users->count() > 0)
                @php
                    $total_count = 0;
                    $i = 1;
                    $status_totals = [];
                @endphp
                @foreach ($users as $user)
                    @foreach ($user->ga as $userga)
                        @php
                            // $total_count += $user->count;
                            $userAllowedStatuses[$user->id] = collect($user->roles)
                                ->flatMap(fn($role) => $roleStatusMap[$role->id] ?? [])
                                ->unique()
                                ->values()
                                ->toArray();
                        @endphp
                        <tr class="">
                            <td class="text-center">{{ $i++ }}</td>
                            <td>{{ $user->emp_id }}</td>
                            <td>{{ $user?->name }}</td>
                            <td>{{ $user->department->name }}</td>
                            <td>
                                @if ($user->roles->count() > 0)
                                    @foreach ($user->roles as $role)
                                        @if ($loop->iteration == 1)
                                            <div class="btn-group w-100">
                                                <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ $role->name }}
                                                </button>
                                                <ul class="dropdown-menu">                
                                        @else
                                            <li class="dropdown-item"><i class="bi bi-ca-alt-fill"></i>&nbsp;{{ $role->name }}</li>
                                        @endif
                                    @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $userga->name }}</td>
                            <td>
                                @if ($user->cas->count() > 0)
                                    @foreach ($user->cas->where('ga_id', $userga->id) as $ca)
                                        @if ($loop->iteration == 1)
                                            <div class="btn-group w-100">
                                                <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ $ca->name }}
                                                </button>
                                                <ul class="dropdown-menu overflow-auto" style="max-height: 200px;">                
                                        @else
                                            <li class="dropdown-item"><i class="bi bi-ca-alt-fill"></i>&nbsp;{{ $ca->name }}</li>
                                        @endif
                                    @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </td>
                            @foreach ($statuses as $status)
                                @switch($status->id)
                                    @case(\App\Enums\ConsumerStatus::REGISTER->value)
                                        @php
                                            $status_id = \App\Enums\ConsumerStatus::PRE_REGISTER->value;
                                        @endphp
                                        @break
                                    @case(\App\Enums\ConsumerStatus::ACCEPT->value)
                                        @php
                                            $status_id = \App\Enums\ConsumerStatus::REGISTER->value;
                                        @endphp
                                        @break
                                    @case(\App\Enums\ConsumerStatus::EXECUTE->value)
                                        @php
                                            $status_id = \App\Enums\ConsumerStatus::ACCEPT->value;
                                        @endphp
                                        @break
                                    @case(\App\Enums\ConsumerStatus::HSC->value)
                                        @php
                                            $status_id = \App\Enums\ConsumerStatus::EXECUTE->value;
                                        @endphp
                                        @break
                                    @case(\App\Enums\ConsumerStatus::ACTIVATE->value)
                                        @php
                                            $status_id = \App\Enums\ConsumerStatus::HSC->value;
                                        @endphp
                                        @break
                                    @default
                                        @php
                                            $status_id = NULL;
                                        @endphp
                                        @break
                                @endswitch
                                @if(in_array($status_id, $userAllowedStatuses[$user->id]))
                                    @php
                                        $status_totals[$status_id] = ($status_totals[$status_id] ?? 0) + ($consumer_counts[$user->id][$userga->id][$status_id] ?? 0);
                                    @endphp
                                    <td class="text-end" nowrap>
                                        <a class="fs-5" href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$userga->id], 'cns_status' => [$status_id], 'charge_area' => $user->cas->where('ga_id', $userga->id)->pluck('id')->toArray()]) }}" target="_blank">{{ $consumer_counts[$user->id][$userga->id][$status_id] ?? 0 }}</a>&nbsp;&nbsp;
                                        <a type="button" href="{{ url('reports/consumer/employee/activity/getUserAssignedTeams') }}?{{ http_build_query(['ga_id'=> [$userga->id],'user_id' => $user->id, 'cns_status' => $status_id, 'charge_area' => $user->cas->where('ga_id', $userga->id)->pluck('id')->toArray(), 'total' => $consumer_counts[$user->id][$userga->id][$status_id] ?? 0]) }}" class="link-canvas" title="Click to view teams List"><i class="bi bi-box-arrow-right fs-4"></i></a>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
                <tr class="table-info">
                    <td colspan="7" class="text-end fw-bold fs-5">Totals</td>
                    @foreach ($statuses as $status_val)
                        @switch($status_val->id)
                            @case(\App\Enums\ConsumerStatus::REGISTER->value)
                                @php
                                    $status_id = \App\Enums\ConsumerStatus::PRE_REGISTER->value;
                                @endphp
                                @break
                            @case(\App\Enums\ConsumerStatus::ACCEPT->value)
                                @php
                                    $status_id = \App\Enums\ConsumerStatus::REGISTER->value;
                                @endphp
                                @break
                            @case(\App\Enums\ConsumerStatus::EXECUTE->value)
                                @php
                                    $status_id = \App\Enums\ConsumerStatus::ACCEPT->value;
                                @endphp
                                @break
                            @case(\App\Enums\ConsumerStatus::HSC->value)
                                @php
                                    $status_id = \App\Enums\ConsumerStatus::EXECUTE->value;
                                @endphp
                                @break
                            @case(\App\Enums\ConsumerStatus::ACTIVATE->value)
                                @php
                                    $status_id = \App\Enums\ConsumerStatus::HSC->value;
                                @endphp
                                @break
                            @default
                                @php
                                    $status_id = NULL;
                                @endphp
                                @break
                        @endswitch
                        <td class="fw-bold fs-5 text-end">{{ $status_totals[$status_id] ?? 0 }}</td>
                    @endforeach
                </tr>
            @else
                <tr>
                    <td colspan="12">No records found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $('#showZeroEmpRows').on('change', function () {
        $('.zero-emp-count').toggleClass('d-none', !this.checked);
    });
</script>
{{-- @include('scripts.link-modal') --}}
@include('scripts.link-canvas')
@include('scripts.export-table', [
    'table' => 'ewcp-table',
    'button' => 'exportBtn2',
    'tabBased' => false,
    'filename' => 'employee-wise-connection-progress',
    'sheet'    => 'Report',
])