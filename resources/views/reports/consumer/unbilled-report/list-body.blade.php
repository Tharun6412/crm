<form name="unbilled-reports-search-form" id="unbilled-reports-search-form"  action="{{ url('reports/unbilled') }}" method="get">
    <div class="d-flex justify-content-between gap-2 mb-2">
        <!-- Invoice Type Filter Card -->
        <div class="d-flex gap-2">
            {{-- <div>
                <div class="form-control">
                    <span class="fw-semibold">Invoice Type</span>
                    <div class="w-auto float-end"><x-master.invoice-type-filter /></div>
                </div>
            </div> --}}
            <div>
                <div class="form-control">
                    <span class="fw-semibold">Segment</span>
                    <div class="w-auto float-end"><x-master.segment-filter /></div>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                <!-- Reset -->
            </div>
            <div>
                <a href="{{ url('reports/unbilled') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
            </div>            
            <!-- Export -->
        </div>
        <div class="text-end">
            <button type="button" id="exportBtn" class="btn btn-outline-info text-end"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-bordered table-hover page-sort table-striped bg-white" id="unbilled-report">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap="nowrap">S No.</th>
                <th>GA Name</th>
                <th class="text-end">Consumers Count</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1; 
            @endphp
            @forelse($gas as $ga)
                @php
                    $count = $consumers[$ga->id]->unbilled_count ?? 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $ga->name }}</td>
                    <td class="text-end"> 
                        @if ($count > 0)
                            <a href="{{ url('reports/unbilled/list') }}?{{ http_build_query([
                                'geo_area' => [$ga->id],
                                'invoice_type' => request()->invoice_type ?? [],
                                ]) }}" class="aging-link" target="_blank">{{ numberFormat($count) }}
                            </a>
                        @else
                        {{ $count }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No GA Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="table-info text-end">
                <th colspan="2" class="text-end">Total</th>
                <th>{{ numberFormat($totalUnbilled) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@include('scripts.link-modal')
@include('scripts.export-table', [
    'table' => 'unbilled-report',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'unbilled_report',
    'sheet'    => 'Report',
])