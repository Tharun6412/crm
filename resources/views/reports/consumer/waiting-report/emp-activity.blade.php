<div class="d-flex flex-row justify-content-between pb-3">
    <div class="justify-content-start">
        {{-- @if ($users->count() > 0)
            <button type="button" id="exportBtn2" class="btn btn-outline-info btn-sm"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
        @endif --}}
    </div>
    @if ($users->count() > 0)
        <div class="form-check form-switch mb-2 justify-content-end fw-semibold">
            <input class="form-check-input custom-check-input" type="checkbox" id="showZeroEmpRows">
            <label class="form-check-label" for="showZeroEmpRows">
                Show All - ({{ $users->count() }})
            </label>
        </div>
    @endif
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Role</th>
                <th>Geo Area</th>
                <th>Charge Areas</th>
                <th>Consumers Waiting</th>
            </tr>
        </thead>
        <tbody>
            @if ($users->count() > 0)
                @php
                    $i = (($users->currentPage() - 1) * $users->perPage())+1;
                    $total_count = 0;
                @endphp
                @foreach ($users as $user)
                    @php
                        $total_count += $user->count;
                    @endphp
                    <tr class="{{ $user->count == 0 ? 'zero-emp-count d-none' : '' }}">
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
                        <td>
                            @if ($user->ga->count() > 0)
                                @foreach ($user->ga as $geo_area)
                                    @if ($loop->iteration == 1)
                                        <div class="btn-group w-100">
                                            <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ $geo_area->name }}
                                            </button>
                                            <ul class="dropdown-menu overflow-auto" style="max-height: 200px;">                
                                    @else
                                        <li class="dropdown-item"><i class="bi bi-ca-alt-fill"></i>&nbsp;{{ $geo_area->name }}</li>
                                    @endif
                                @endforeach
                                    </ul>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if ($user->cas->count() > 0)
                                @foreach ($user->cas as $ca)
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
                        <td class="text-center"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['geo_area' => $user->ga->pluck('id')->toArray(), 'charge_area' => $user->cas->pluck('id')->toArray(), 'cns_status' => [2,3,4,5], 'date_from' => request()->date_from, 'date_to' => request()->date_to]) }}" target="_blank">{{ $user->count ?? 0 }}</a></td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="7" class="text-end">Totals</td>
                        <td class="text-center">{{ $total_count }}</td>
                    </tr>
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