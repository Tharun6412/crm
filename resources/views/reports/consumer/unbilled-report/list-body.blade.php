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
                <th width="1%" nowrap="nowrap" rowspan="2">S No.</th>
                <th rowspan="2">GA Name</th>
                <th colspan="2" class="text-center">Billable</th>
                <th colspan="4" class="text-center">Unbilled</th>
                <th rowspan="2" class="text-center">Billed</th>
            </tr>
            <tr>
                <th class="text-end">Active</th>
                <th class="text-end">Billable</th>
                <th class="text-end">60 - 90 days</th>
                <th class="text-end">90 - 120 days</th>
                <th class="text-end">> 120 Days</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1; 
                $grand_total = 0;
            @endphp
            @forelse($gas as $ga)
                @php
                    $active_count = $consumers[$ga->id]->total_active ?? 0;
                    $bill_count = $consumers[$ga->id]->eligible_count ?? 0;
                    $count_60_90 = $consumers[$ga->id]->unbilled_60_90 ?? 0;
                    $count_90_120 = $consumers[$ga->id]->unbilled_90_120 ?? 0;
                    $count_gt_120 = $consumers[$ga->id]->unbilled_120_plus ?? 0;
                    $total_ga_unbilled = ($count_60_90 + $count_90_120 + $count_gt_120);
                    $grand_total += $total_ga_unbilled;
                    $billed_count = $bill_count - $total_ga_unbilled;
                @endphp
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $ga->name }}</td>
                    <td class="text-end">{{ $active_count }}</td>
                    <td class="text-end">{{ $bill_count }}</td>
                    <td class="text-end"> 
                        @if ($count_60_90 > 0)
                            <a href="{{ url('reports/unbilled/list') }}?{{ http_build_query([
                                'geo_area' => [$ga->id],
                                'aging'        => '60_90',
                                'segments'     => request()->segments ?? [],
                                'invoice_type' => request()->invoice_type ?? [],
                                ]) }}" class="aging-link" target="_blank">{{ numberFormat($count_60_90) }}
                            </a>
                        @else
                        {{ $count_60_90 }}
                        @endif
                    </td>
                    <td class="text-end"> 
                        @if ($count_90_120 > 0)
                            <a href="{{ url('reports/unbilled/list') }}?{{ http_build_query([
                                'geo_area' => [$ga->id],
                                'aging'        => '90_120',
                                'segments'     => request()->segments ?? [],
                                'invoice_type' => request()->invoice_type ?? [],
                                ]) }}" class="aging-link" target="_blank">{{ numberFormat($count_90_120) }}
                            </a>
                        @else
                        {{ $count_90_120 }}
                        @endif
                    </td>
                    <td class="text-end"> 
                        @if ($count_gt_120 > 0)
                            <a href="{{ url('reports/unbilled/list') }}?{{ http_build_query([
                                'geo_area' => [$ga->id],
                                'aging'        => 'gt_120',
                                'segments'     => request()->segments ?? [],
                                'invoice_type' => request()->invoice_type ?? [],
                                ]) }}" class="aging-link" target="_blank">{{ numberFormat($count_gt_120) }}
                            </a>
                        @else
                        {{ $count_gt_120 }}
                        @endif
                    </td>
                    <td class="text-end"> 
                        @if ($total_ga_unbilled > 0)
                            <a href="{{ url('reports/unbilled/list') }}?{{ http_build_query([
                                'geo_area' => [$ga->id],
                                'aging'        => 'all',
                                'segments'     => request()->segments ?? [],
                                'invoice_type' => request()->invoice_type ?? [],
                                ]) }}" class="aging-link" target="_blank">{{ numberFormat($total_ga_unbilled) }}
                            </a>
                        @else
                        {{ $total_ga_unbilled }}
                        @endif
                    </td>
                    <td class="text-end">
                        {{ $billed_count }}
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
                <th>{{ numberFormat($totals->total_active) }}</th>
                <th>{{ numberFormat($totals->eligible_count) }}</th>
                <th>{{ numberFormat($totals->unbilled_60_90) }}</th>
                <th>{{ numberFormat($totals->unbilled_90_120) }}</th>
                <th>{{ numberFormat($totals->unbilled_120_plus) }}</th>
                <th>{{ numberFormat($grand_total) }}</th>
                <th>{{ numberFormat($totals->eligible_count - $grand_total) }}</th>
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