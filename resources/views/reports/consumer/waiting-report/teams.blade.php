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
                    <td class="fw-semibold">
                        {{ $ga->name }}
                    </td>
                    @foreach ($departments as $department)
                        @php
                            $teamList = $teamData[$ga->id][$department->id] ?? [];
                        @endphp
                        <td class="text-center">
                            @if(count($teamList))
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown"><i class="bi bi-people"></i> Teams {{ count($teamList) }}</button>
                                    <ul class="dropdown-menu">
                                        @foreach($teamList as $team)
                                             <li>
                                                <a href="{{ url('admin/teams/show/'.$team->id) }}"
                                                class="dropdown-item link-modal">
                                                   <i class="bi bi-person"></i> {{ $team->name }} - {{ $team->users_count }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <span class="text-body-tertiary"><i class="bi bi-person-slash"></i></span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot class="table-info">
                <th class="text-end">Total Teams/Users</th>
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
                    <td class="text-center fw-semibold">
                        Teams : {{ $totalTeams }} <i class="bi bi-dash-lg"></i> Users : {{ $totalUsers }}
                    </td>
                @endforeach
            </tr>
        </tfoot>
    </table>
</div>
@include('scripts.link-modal')