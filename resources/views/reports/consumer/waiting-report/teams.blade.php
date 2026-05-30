@php
    $teamData = [];
    foreach ($teams as $team) {
        $teamData[$team->ga_id][$team->department_id][] = $team;
    }
@endphp
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>GA</th>
                @foreach ($departments as $department)
                    <th class="text-center">
                        {{ $department->name }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($geo_areas as $ga)
                <tr>
                    <td class="fw-bold">
                        {{ $ga->name }}
                    </td>
                    @foreach ($departments as $department)
                        @php
                            $teamList = $teamData[$ga->id][$department->id] ?? [];
                        @endphp
                        <td>
                            @if(count($teamList))
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">Teams {{ count($teamList) }}</button>
                                    <ul class="dropdown-menu">
                                        @foreach($teamList as $team)
                                             <li>
                                                <a href="{{ url('admin/teams/show/'.$team->id) }}"
                                                class="dropdown-item link-modal">
                                                    {{ $team->name }} - {{ $team->users_count }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <span class="text-muted">No Teams</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
                <th>Total</th>
                @foreach ($departments as $department)
                    @php
                        $totalTeams = 0;
                        $totalUsers = 0;
                        foreach ($teams as $team) {
                            if ($team->department_id == $department->id) {
                                $totalTeams++;
                                $totalUsers += $team->users_count;
                            }
                        }
                    @endphp
                    <td>
                        Teams : {{ $totalTeams }} Users : {{ $totalUsers }}
                    </td>
                @endforeach
            </tr>
        </tfoot>
    </table>
</div>
@include('scripts.link-modal')