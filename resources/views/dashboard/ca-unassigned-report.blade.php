<div class="offcanvas-header bg-secondary-subtle">
    <h4>UnAssigned Consumers</h4>&nbsp;&nbsp;
    <!-- Export -->    
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
</div>
<div class="offcanvas-body">
    <div class="d-flex flex-row justify-content-between pb-3">
        <div class="justify-content-start">
            @if ($charge_areas->count() > 0)
                <button type="button" id="exportBtn2" class="btn btn-outline-info btn-sm"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
            @endif
        </div>
        <div class="form-check form-switch mb-2 justify-content-end fw-semibold">
            <input class="form-check-input custom-check-input" type="checkbox" id="showZeroAreaRows">
            <label class="form-check-label" for="showZeroAreaRows">
                Show All - ({{ $charge_areas->count() }})
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
            @if ($charge_areas->count() > 0)
                @foreach ($charge_areas as $ca)
                    @php
                        $tot_count += $ca->ca_count;
                    @endphp
                    <tr class="{{ $ca->ca_count == 0 ? 'zero-area-count d-none' : '' }}">
                        <td width="1%" class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $ca->name }}</td>
                        <td class="text-end">
                            <a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [request()->cns_status], 'geo_area' => [$ca->ga->id], 'charge_area' => [$ca->id], 'status' => [2]]) }}" target="_blank">
                            {{ $ca->ca_count ?? 0}}
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
@include('scripts.export-table', [
    'table' => 'area-report-table',
    'button' => 'exportBtn2',
    'tabBased' => false,
    'filename' => 'consumer_progress_area_report',
    'sheet'    => 'Report',
])