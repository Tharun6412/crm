<div class="offcanvas-header bg-secondary-subtle">
    <h4>Ageing Progress Report - {{ $status_name ?? '' }}</h4>&nbsp;&nbsp;
    <!-- Export -->    
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
</div>
<div class="offcanvas-body">
    <div class="d-flex flex-row justify-content-between pb-3">
        <div class="justify-content-start">
            {{-- @if ($areas->count() > 0)
                <button type="button" id="exportBtn2" class="btn btn-outline-info btn-sm"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
            @endif --}}
        </div>
    </div>
    <table class="table table-bordered table-striped table-light" id="area-report-table">
        <thead class="table-info">
            <tr>
                <th>Progress (Days)</th>
                <th>Consumers</th>
            </tr>
        </thead>
        <tbody>
            @php
                $ageing_loop = [
                    "days_0_30" => "30-",
                    "days_31_60" => "31-60",
                    "days_61_90" => "61-90",
                    "days_91_180" => "91-180",
                    "days_180_plus" => "180+",
                ];
                $count = 0;
            @endphp
            @foreach ($ageing_loop as $key => $item)
                @php
                    $ageing_count = $ageing_consumers[$key] ?? 0;
                    $count += $ageing_count;
                @endphp
                <tr>
                    <td>
                        {{ $ageing_loop[$key] == "30-" ? '0-30': $item }}
                    </td>
                    <td>
                        <a href="{{ url('reports/consumer/consumerAgeingReport/consumersList') }}?{{ http_build_query([
                            'geo_area' => [request()->ga_id],
                            'range' => $item,
                            'connection_type_id' => request()->connect_type_id,
                            'segments' => request()->onboard_segment_id ?? [],
                            'cns_status' => [request()->cns_status],
                            'type' => 1,
                            ]) }}" class="aging-link" target="_blank">{{ numberFormat($ageing_count) }}
                        </a>
                    </td>
                </tr>
                @endforeach
                <tr class="bg bg-warning-subtle">
                    <td>Total</td>
                    <td>{{ numberFormat($count) }}</td>
                </tr>
        </tbody>
    </table>
</div>
@include('scripts.export-table', [
    'table' => 'area-report-table',
    'button' => 'exportBtn2',
    'tabBased' => false,
    'filename' => 'consumer_progress_area_report',
    'sheet'    => 'Report',
])