{{-- Show --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content ">
        <div class="modal-header bg-secondary-subtle">
            <h4 class="modal-title fw-semibold"><i class="bi bi-people-fill"></i>&nbsp;View Delivery Unit Details - <span class="fw-bold text-primary"> {{ $delivery_unit->name }}</span></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="p-1">
                <x-lms.du-details :du="$delivery_unit"/>
            </div>
            {{-- Charge Areas Table --}}
            <div class="card border shadow-sm mt-2">
                <div class="card-header bg-info-subtle fw-semibold"><i class="bi bi-pin-map-fill text-secondary"></i>&nbsp;Area List</div>
                <div class="row mx-1">
                    @if ($delivery_unit->areas->count() > 0)
                        @foreach ($delivery_unit->areas->groupBy('ca_id') as $areas)
                            <div class="col-12 mt-3">
                                <h4 class="text-primary border-bottom pb-2">
                                    {{ $areas->first()->ca->name }}
                                </h4>
                            </div>
                            @foreach ($areas as $area)
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="p-2">
                                        <i class="bi bi-geo-fill text-success"></i>&nbsp;{{ $area->name }}
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
                    {{-- @if ($delivery_unit->areas->count() > 0)
                        @foreach ($delivery_unit->areas as $area)                            
                            <div class="col-3">
                                <div class="border border-secondary-subtle rounded-2 p-2 m-2 text-secondary">
                                    <i class="bi bi-geo-alt-fill"></i>&nbsp;{{ $area->name }}
                                </div>
                            </div>
                        @endforeach
                    @else
                       <div class="col"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No Areas  Found</div>
                    @endif --}}
                </div>
            </div>
            {{-- Teams Table --}}
            <div class="card border shadow-sm mt-2">
                <div class="card-header bg-info-subtle fw-semibold"><i class="bi bi-person-lines-fill text-secondary"></i>&nbsp;Team List</div>
                <div class="table-responsive p-2">
                    <table class="table table-bordered table-hover align-middle mb-3">
                        <thead class="table-secondary">
                            <tr>
                                <th width="1%" class="text-center">S.No</th>
                                <th>Name</th>
                                <th>Team Lead</th>
                                <th>Department</th>
                                <th>Areas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($du_teams->count() > 0)
                                @foreach ($du_teams as $team)
                                    <tr>
                                        <td class="text-center fw-semibold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>{{ $team->name }}</td>
                                        <td>{{ $team->responsibleUser?->name }} - {{ $team->responsibleUser?->emp_id }}</td>
                                        <td>{{ $team->departments?->name }}</td>
                                        <td>
                                            @if ($team->areas->count() > 0)
                                                @foreach ($team->areas as $area)
                                                    @if ($loop->iteration == 1)
                                                        <div class="btn-group w-100">
                                                            <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                {{ $area->name }}
                                                            </button>
                                                            <ul class="dropdown-menu">                
                                                    @else
                                                        <li class="dropdown-item"><i class="bi bi-ca-alt-fill"></i>&nbsp;{{ $area->name }}</li>
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
                                    <td colspan="4" class="text-center text-muted py-4">No Teams Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer bg-white">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i>&nbsp;Close</button>
        </div>
    </div>
</div>