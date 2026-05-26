{{-- Employee collection show details --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Consumer Waiting Report&nbsp;-&nbsp;{{ $status_name ?? "ALL" }}&nbsp;-&nbsp;{{ $ga_name->name ?? "ALL"}}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer sattus --}}
            @if ($users_list->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Employee Type</th>
                            <th>Charge Areas</th>
                            <th>Consumers Waiting</th>
                            <th>Available Teams</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users_list as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user?->name }}</td>
                                <td>{{ $user->department?->name }}</td>
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
                                <td>{{ $user->employeeType?->name }}</td>
                                <td>
                                    @if ($user->cas->where('ga_id', $ga_name->id)->count() > 0)
                                        @foreach ($user->cas->where('ga_id', $ga_name->id) as $ca)
                                            @if ($loop->iteration == 1)
                                                <div class="btn-group w-100">
                                                    <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        {{ $ca->name }}
                                                    </button>
                                                    <ul class="dropdown-menu">                
                                            @else
                                                <li class="dropdown-item"><i class="bi bi-ca-alt-fill"></i>&nbsp;{{ $ca->name }}</li>
                                            @endif
                                        @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center"><a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga_name->id], 'cns_status' => [request()->cns_status], 'charge_area' => $user->cas->where('ga_id', $ga_name->id)->pluck('id')->unique()->values()->toArray()]) }}" target="_blank">{{ $user->count ?? 0 }}</a></td>
                                <td>
                                    @if ($user->team->count() > 0)
                                        <div class="btn-group w-100">
                                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                View Teams ({{ $user->team->count() }})
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach ($user->team as $team)
                                                    <li class="dropdown-item">
                                                        {{ $team->name }}&nbsp;-&nbsp;<strong>{{ $team->users_count }}&nbsp;Employees</strong>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    No employees available for the selected Status.
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>