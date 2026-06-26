<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-primary">
            <tr>
                <th rowspan="2" width="1%">S.No</th>
                <th rowspan="2">Team</th>
                <th rowspan="2">Team Coordinator</th>
                <th rowspan="2">Department</th>
                <th colspan="3" class="text-center">Assigned Consumers</th>
            </tr>
            <tr>
                <th class="bg-primary bg-opacity-75 text-center">Progress</th>
                <th class="bg-primary bg-opacity-75 text-center">Completed</th>
                <th class="bg-primary bg-opacity-75 text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            @if ($teams->count() > 0)
                @php
                    $progress_count = 0;
                    $completed_count = 0;
                    $total_count = 0;
                @endphp
                @foreach ($teams as $team)
                    @php
                        $progress = $consumer_team_assign[$team->id][0] ?? 0;
                        $complete = $consumer_team_assign[$team->id][1] ?? 0;
                        $total = ($consumer_team_assign[$team->id][0] ?? 0) + ($consumer_team_assign[$team->id][1] ?? 0);
                        // Totals
                        $progress_count += $progress;
                        $completed_count += $complete;
                        $total_count += $total;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ url('admin/teams/show/'.$team->id) }}" class="link-modal">{{ $team->name }}</a></td>
                        <td>{{ $team->responsibleUser?->name }}</td>
                        <td>{{ $team->departments->name ?? '' }}</td>
                        <td class="text-center"><a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'date_from' => request()->team_date_from, 'date_to' => request()->team_date_to, 'team_id' => $team->id, 'status' => [0]]) }}" target="_blank">{{ $progress }}</a></td>
                        <td class="text-center">
                            <a href="{{ url('reports/consumer/teamProgress/employeeProgress/'.$team->id) }}?{{ http_build_query(['date_from' => request()->team_date_from, 'date_to' => request()->team_date_to]) }}" class="link-modal">{{ $complete }}</a>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'date_from' => request()->team_date_from, 'date_to' => request()->team_date_to, 'team_id' => $team->id, 'status' => [0, 1]]) }}" target="_blank">
                                {{ $total }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="4" class="text-end">Totals</td>
                        <td class="text-center"><a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'date_from' => request()->team_date_from, 'date_to' => request()->team_date_to, 'status' => [0]]) }}" target="_blank">{{ $progress_count }}</a></td>
                        <td class="text-center"><a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'date_from' => request()->team_date_from, 'date_to' => request()->team_date_to, 'status' => [1]]) }}" target="_blank">{{ $completed_count }}</a></td>
                        <td class="text-center"><a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'date_from' => request()->team_date_from, 'date_to' => request()->team_date_to, 'status' => [0,1]]) }}" target="_blank">{{ $total_count }}</a></td>
                    </tr>
            @else
                <tr class="alert alert-primary">
                    <td colspan="6">No Teams found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@include('scripts.link-modal')