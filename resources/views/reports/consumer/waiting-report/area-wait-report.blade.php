<div class="offcanvas-header bg-secondary-subtle">
    <h4>Charge Area - {{ request()->ca_name ?? ''}}</h4>&nbsp;&nbsp;
    <!-- Export -->    
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
</div>
<div class="offcanvas-body">
    <div class="d-flex flex-row justify-content-between pb-3">
        <div class="justify-content-start">
            @if ($areas->count() > 0)
                <button type="button" id="exportBtn2" class="btn btn-outline-info btn-sm"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
            @endif
        </div>
        <div class="form-check form-switch mb-2 justify-content-end fw-semibold">
            <input class="form-check-input custom-check-input" type="checkbox" id="showZeroAreaRows">
            <label class="form-check-label" for="showZeroAreaRows">
                Show All - ({{ $areas->count() }})
            </label>
        </div>
    </div>
    <table class="table table-bordered table-striped table-light" id="area-report-table">
        <thead class="table-info">
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th class="text-end">Consumers</th>
            </tr>
        </thead>
        <tbody>
            @php
                $tot_count = 0;
            @endphp
            @if ($areas->count() > 0)
                @foreach ($areas as $area)
                    @php
                        $tot_count += $area->area_count;
                    @endphp
                    <tr class="{{ $area->area_count == 0 ? 'zero-area-count d-none' : '' }}">
                        <td width="1%" class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $area->name }}</td>
                        <td class="text-end">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [request()->ga_id], 'cns_status' => [request()->cns_status], 'charge_area' => [request()->ca_id],'area' => [$area->id], 'segments' => [request()->segments], 'connection_type_id' => [request()->connection_type_id]]) }}" target="_blank" title="Consumers List">
                            {{ $area->area_count ?? 0}}
                            </a>
                        </td>
                    </tr>
                @endforeach
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end">Total Consumers</td>
                        <td class="text-end">{{ $tot_count }}</td>
                    </tr>
            @else
                <tr>
                    <td colspan="3">No Areas found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $('#showZeroAreaRows').on('change', function () {
        $('.zero-area-count').toggleClass('d-none', !this.checked);
    });
</script>
<style>
    .offcanvas {
        z-index: 1060 !important;
    }
    .offcanvas-backdrop {
        z-index: 1055 !important;
    }
    .custom-check-input {
        height: 1.15em;
        border: 1px solid #333;
    }
</style>
@include('scripts.export-table', [
    'table' => 'area-report-table',
    'button' => 'exportBtn2',
    'tabBased' => false,
    'filename' => 'consumer_progress_area_report',
    'sheet'    => 'Report',
])