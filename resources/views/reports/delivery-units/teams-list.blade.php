<div class="offcanvas-header bg-secondary-subtle">
    <h4>Teams List</h4>
    <!-- Export -->    
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
</div>
<div class="offcanvas-body">
    <div class="d-flex flex-row justify-content-between pb-3">
        <div>&nbsp;</div>
        @if ($teams->count() > 0)
            <div>
                <button type="button" id="exportBtn2" class="btn btn-outline-info btn-sm"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
            </div>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="du-report-table">
            <thead>
                <tr>
                    <th rowspan="2">S.No</th>
                    <th rowspan="2">Name</th>
                    <th colspan="3" class="text-center">Consumers</th>
                </tr>
                <tr>
                    <th title="Assigned">Progress</th>
                    <th title="Completed">Completed</th>
                    <th title="Total">Total</th>
                </tr>
            </thead>
            <tbody>
                @if ($teams->count() > 0)
                    @php
                        $assign_count = $completed_count = $total_count = 0;
                        $team_ids = [];
                    @endphp
                    @foreach ($teams as $team)
                        @php
                            $team_ids[] = $team->id;
                            $assigned_val = $team_consumers[$team->id][0] ?? 0;
                            $completed_val = $team_consumers[$team->id][1] ?? 0;
                            $total_val = $assigned_val + $completed_val;
                            // Total Calculations
                            $assign_count += $assigned_val;
                            $completed_count += $completed_val;
                            $total_count += $total_val;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-nowrap">{{ $team->name }}</td>
                            <td>
                                <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'team_id' => $team->id, 'status' => [0], 'date_from' => request()->du_date_from, 'date_to' => request()->du_date_to]) }}" class="fs-5" target="_blank">{{ $assigned_val }}</a>
                            </td>
                            <td>
                                <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'team_id' => $team->id, 'status' => [1], 'date_from' => request()->du_date_from, 'date_to' => request()->du_date_to]) }}" class="fs-5" target="_blank">{{ $completed_val }}</a>    
                            </td>
                            <td>
                                <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'team_id' => $team->id, 'status' => [0,1], 'date_from' => request()->du_date_from, 'date_to' => request()->du_date_to]) }}" class="fs-5" target="_blank">{{ $total_val }}</a>
                            </td>
                        </tr>
                    @endforeach
                        <tr>
                            <td colspan="2" class="text-end">Totals</td>
                            <td>
                                <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'team_ids' => $team_ids, 'status' => [0], 'date_from' => request()->du_date_from, 'date_to' => request()->du_date_to]) }}" class="fs-5" target="_blank">{{ $assign_count }}</a>
                            </td>
                            <td>
                                <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'team_ids' => $team_ids, 'status' => [1], 'date_from' => request()->du_date_from, 'date_to' => request()->du_date_to]) }}" class="fs-5" target="_blank">{{ $completed_count }}</a>
                            </td>
                            <td>
                                <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'team_ids' => $team_ids, 'status' => [0,1], 'date_from' => request()->du_date_from, 'date_to' => request()->du_date_to]) }}" class="fs-5" target="_blank">{{ $total_count }}</a>
                            </td>
                        </tr>
                @else
                    <tr>
                        <td colspan="5">No records found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@include('scripts.export-table', [
    'table' => 'du-report-table',
    'button' => 'exportBtn2',
    'tabBased' => false,
    'filename' => 'du-teams-progress',
    'sheet'    => 'Report',
])