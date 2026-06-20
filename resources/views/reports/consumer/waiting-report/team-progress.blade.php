<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-primary">
            <tr>
                <th rowspan="2" width="1%">S.No</th>
                <th rowspan="2">Team</th>
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
                @foreach ($teams as $team)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $team->name }}</td>
                        <td>{{ $team->departments->name ?? '' }}</td>
                        <td class="text-center">{{ $consumer_team_assign[$team->id][0] ?? 0 }}</td>
                        <td class="text-center">
                            <a href="{{ url('reports/consumer/teamProgress/employeeProgress/'.$team->id) }}" class="link-modal">{{ $consumer_team_assign[$team->id][1] ?? 0 }}</a>
                        </td>
                        <td class="text-center">{{ ($consumer_team_assign[$team->id][0] ?? 0) + ($consumer_team_assign[$team->id][1] ?? 0) }}</td>
                    </tr>
                @endforeach
            @else
                <tr class="alert alert-primary">
                    <td colspan="6">No Teams found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@include('scripts.link-modal')