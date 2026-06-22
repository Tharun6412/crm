<div class="offcanvas-header bg-secondary-subtle">
    <h4>Assigned Teams</h4>&nbsp;&nbsp;
    <!-- Export -->    
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
</div>
<div class="offcanvas-body">
    <table class="table table-bordered table-striped table-light" id="user-assign-table">
        <thead class="table-info">
            <tr>
                <th>S.No</th>
                <th>Team</th>
                <th class="text-center">Assigned Consumers</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_count = 0;
            @endphp
            @if ($teams->count() > 0)
                @foreach ($teams as $team)
                    @php
                        $assgined = $assigned_consumers[$team->id] ?? 0;
                        $total_count += $assgined;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $team->name ?? '' }}</td>
                        <td class="text-center">
                            <a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(
                                [
                                    'cns_status' => [$status_id ?? NULL], 
                                    'target_status' => [$status_id+1],
                                    'ugas' => $user->ga->pluck('id')->toArray(), 
                                    'ucas' => $user->ca->pluck('id')->toArray(), 
                                    'status' => [0], 
                                    'team_id' => [$team->id]
                                ]) }}" target="_blank">
                                {{ $assgined }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="2" class="text-end">Total Assigned</td>
                        <td class="text-center">{{ $total_count }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end">Unassigned</td>
                        <td class="text-center">{{ $unassigned }}</td>
                    </tr>
            @else
                <tr>
                    <td colspan="4">No Teams found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@include('scripts.export-table', [
    'table' => 'user-assign-table',
    'button' => 'exportBtn2',
    'tabBased' => false,
    'filename' => 'consumer_progress_area_report',
    'sheet'    => 'Report',
])