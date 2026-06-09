<div class="d-flex flex-row justify-content-between pb-3">
    <div class="justify-content-start">
        {{-- @if ($users->count() > 0)
            <button type="button" id="exportBtn2" class="btn btn-outline-info btn-sm"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
        @endif --}}
    </div>
    {{-- @if ($users->count() > 0)
        <div class="form-check form-switch mb-2 justify-content-end fw-semibold">
            <input class="form-check-input custom-check-input" type="checkbox" id="showZeroEmpRows">
            <label class="form-check-label" for="showZeroEmpRows">
                Show All - ({{ $users->count() }})
            </label>
        </div>
    @endif --}}
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2">S.No</th>
                <th rowspan="2">Employee ID</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Department</th>
                <th rowspan="2">Role</th>
                <th rowspan="2">Geo Area</th>
                <th rowspan="2">Charge Areas</th>
                <th colspan="{{ $statuses->count() }}" class="text-center">Consumers Waiting</th>
            </tr>
            <tr>
                @foreach ($statuses as $status)
                    <th>{{ $status->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if ($users->count() > 0)
                @php
                    $i = (($users->currentPage() - 1) * $users->perPage())+1;
                    $total_count = 0;
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
                            <td>{{ $i++ }}</td>
                            <td>{{ $user->emp_id }}</td>
                            <td>{{ $user?->name }}</td>
                            <td>{{ $user->department->name }}</td>
                            <td>
                                @if ($user->roles->count() > 0)
                                    @foreach ($user->roles as $role)
                                        @if ($loop->iteration == 1)
                                            <div class="btn-group w-100">
                                                <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
                                    <td>
                                        <a href="{{ url('reports/consumer/waiting/consumersListForEmployees') }}?{{ http_build_query(['ga_id'=> $userga->id, 'cns_status' => $status_id]) }}" class="link-modal">{{ $statsMap[$user->id][$userga->id][$status_id] ?? 0 }}</a>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                            {{-- <td class="text-center"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['geo_area' => $user->ga->pluck('id')->toArray(), 'charge_area' => $user->cas->pluck('id')->toArray(), 'cns_status' => [2,3,4,5], 'date_from' => request()->date_from, 'date_to' => request()->date_to]) }}" target="_blank">{{ $user->count ?? 0 }}</a></td> --}}
                        </tr>
                    @endforeach
                @endforeach
                    {{-- <tr>
                        <td colspan="7" class="text-end">Totals</td>
                        <td class="text-center">{{ $total_count }}</td>
                    </tr> --}}
            @else
                <tr>
                    <td colspan="8">No records found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{-- load utils file for pagination --}}
    @if ($users->count() > 0)
        <div class="col-sm-12">
            {{ $users->links('utils.paginator', ['modDiv' => 'report-emp-activity-list']) }}
        </div>
    @endif
</div>
<script type="text/javascript">
    $('#showZeroEmpRows').on('change', function () {
        $('.zero-emp-count').toggleClass('d-none', !this.checked);
    });
</script>
@include('scripts.link-modal')