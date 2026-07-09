{{-- Show --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content ">
        <div class="modal-header bg-secondary-subtle">
            <h5 class="modal-title fw-semibold"><i class="bi bi-people-fill"></i>&nbsp;Team - {{ $team->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="p-1">
                <x-admin.teams-details :teams="$team"/>
            </div>
            {{-- Employees Table --}}
            <div class="card border shadow-sm">
                <div class="card-header bg-info-subtle fw-semibold"><i class="bi bi-person-lines-fill text-secondary"></i>&nbsp;Employees List <span class="float-end">Team Coordinator - {{ $team->responsibleUser->name }} ({{ $team->responsibleUser->emp_id }})</span></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-3">
                        <thead class="table-secondary">
                            <tr>
                                <th width="1%" class="text-center">S.No</th>
                                <th>Employee Id</th>
                                <th>Employee Name</th>
                                <th>Roles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($team->users->count() > 0)
                                @foreach ($team->users as $user)
                                @php
                                $value="";
                                    if($team->responsible_user_id == $user->id){
                                        $value = "Team Coordinator";
                                    }
                                @endphp
                                    <tr>
                                        <td class="text-center fw-semibold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>{{ $user->emp_id }}</td>
                                        <td>{{ $user->name }} {{ !empty($value) ? "(".$value.")" : '' }}</td>
                                        <td>
                                            @if ($user->roles->count() > 0)
                                                @foreach ($user->roles as $role)
                                                    @if ($loop->iteration == 1)
                                                        <div class="btn-group">
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
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No Records Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Charge Areas Table --}}
            <div class="card border shadow-sm mt-2">
                <div class="card-header bg-info-subtle fw-semibold"><i class="bi bi-pin-map-fill text-secondary"></i>&nbsp;Charge Areas and Areas List</div>
                <div class="row gap-2 mx-2">
                    @if ($team->areas->count() > 0)
                        @foreach ($team->areas->groupBy('ca_id') as $areas)
                            <div class="col-12 mt-3">
                                <h5 class="text-primary fw-bold border-bottom pb-2">
                                    {{ $areas->first()->ca->name }}
                                </h5>
                            </div>
                            @foreach ($areas as $area)
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="border border-secondary-subtle rounded-2 p-2 text-secondary">
                                        <i class="bi bi-geo-alt-fill"></i>&nbsp;{{ $area->name }}
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @else
                        <div class="col text-center">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No Areas Found
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="modal-footer bg-white">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i>&nbsp;Close</button>
        </div>
    </div>
</div>