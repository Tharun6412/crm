{{-- Show --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content ">
        <div class="modal-header bg-secondary-subtle">
            <h5 class="modal-title fw-semibold"><i class="bi bi-people-fill"></i>&nbsp;Team - {{ $team->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body bg-light">
            <div>
                <x-admin.teams-details :teams="$team"/>
            </div>
            {{-- Employees Table --}}
            <div class="card border shadow-sm">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-person-lines-fill text-secondary"></i>&nbsp;Employees List</div>
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
                                    <tr>
                                        <td class="text-center fw-semibold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>{{ $user->emp_id }}</td>
                                        <td>{{ $user->name }}</td>
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
                                    <td colspan="2" class="text-center text-muted py-4"> <i class="bi bi-inbox fs-3 d-block mb-2"></i>No Employees Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Charge Areas Table --}}
            <div class="card border shadow-sm mt-2">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-pin-map-fill text-secondary"></i>&nbsp;Charge Area List</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mt">
                        <thead class="table-secondary">
                            <tr>
                                <th width="1%" class="text-center">S.No</th>
                                <th>Charge Area</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($team->cas->count() > 0)
                                @foreach ($team->cas as $ca)
                                    <tr>
                                        <td class="text-center fw-semibold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td><i class="bi bi-geo-alt-fill text-secondary"></i>&nbsp;{{ $ca->name }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4"> <i class="bi bi-inbox fs-3 d-block mb-2"></i>No Charage Areas  Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer bg-white">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i>&nbsp;Close</button>
        </div>
    </div>
</div>