<div class="offcanvas-header">
    <h5>Charge Area - {{ request()->ca_name ?? ''}}</h5>&nbsp;&nbsp;
    <!-- Export -->
    @if ($areas->count() > 0)
        <button type="button" id="exportBtn2" class="btn btn-outline-info"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
    @endif
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
</div>
<div class="offcanvas-body">
    <div class="form-check form-switch mb-2">
        <input class="form-check-input" type="checkbox" id="showZeroAreaRows">
        <label class="form-check-label" for="showZeroAreaRows">
            Show All - ({{ $areas->count() }})
        </label>
    </div>
    <table class="table table-bordered" id="area-report-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th class="text-nowrap">Consumers Waiting</th>
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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $area->name }}</td>
                        <td class="text-center">{{ $area->area_count ?? 0}}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="2" class="text-end">Totals</td>
                        <td class="text-center">{{ $tot_count }}</td>
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
</style>
@include('scripts.export-table', [
    'table' => 'area-report-table',
    'button' => 'exportBtn2',
    'tabBased' => false,
    'filename' => 'consumer_progress_area_report',
    'sheet'    => 'Report',
])