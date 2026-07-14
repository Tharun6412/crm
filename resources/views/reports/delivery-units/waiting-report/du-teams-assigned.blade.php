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
        <table class="table table-bordered" id="du-assign">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Assigned</th>
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
                            // Total Calculations
                            $assign_count += $assigned_val;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-nowrap">{{ $team->name }}</td>
                            <td class="text-center">
                                <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'team_id' => $team->id, 'status' => [0]]) }}" class="fs-5" target="_blank">{{ $assigned_val }}</a>
                            </td>
                        </tr>
                    @endforeach
                        <tr>
                            <td colspan="2" class="text-end">Total Assigned</td>
                            <td class="text-center">
                                <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(['geo_area' => [$team->ga_id], 'team_ids' => $team_ids, 'status' => [0]]) }}" class="fs-5" target="_blank">{{ $assign_count }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end">Unassigned</td>
                            <td colspan="2" class="text-center">{{ request()->total - $assign_count }}</td>
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
    'table' => 'du-assign',
    'button' => 'exportBtn2',
    'tabBased' => false,
    'filename' => 'delivery-unit-assign-list',
    'sheet'    => 'Report',
])