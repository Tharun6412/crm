<form name="aging-reports-search-form" id="aging-reports-search-form"  action="{{ url('reports/ageingReport') }}" method="get">
    <div class="d-flex justify-content-between gap-2 mb-2">
        <!-- Invoice Type Filter Card -->
        <div class="d-flex gap-2">
            <div>
                <div class="form-control">
                    <span class="fw-semibold">Invoice Type</span>
                    <div class="w-auto float-end"><x-master.invoice-type-filter /></div>
                </div>
            </div>
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
                <a href="{{ url('reports/ageingReport') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
            </div>            
            <!-- Export -->
        </div>
        <div class="text-end">
            <button type="button" id="exportBtn" class="btn btn-outline-info text-end"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-bordered table-hover page-sort table-striped bg-white" id="aging-report">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap="nowrap">S No.</th>
                <th>GA Name</th>
                <th class="text-end">No Due Days</th>
                <th class="text-end">1–15 Days</th>
                <th class="text-end">16–30 Days</th>
                <th class="text-end">31–60 Days</th>
                <th class="text-end">61–90 Days</th>
                <th class="text-end">> 90 Days</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1; 
                $total_no_due = $total_range_15 = $total_range_30 = $total_range_60 = $total_range_90 = $total_range_gt90 = 0;
            @endphp
            @forelse($gas as $ga)
                @php 
                    $gaInvoices = $invoices[$ga->id] ?? '';
                    $inv['no_due_days'] = $gaInvoices->no_due_days ?? 0;
                    $inv['range_1_15'] = $gaInvoices->range_1_15 ?? 0;
                    $inv['range_16_30'] = $gaInvoices->range_16_30 ?? 0;
                    $inv['range_31_60'] = $gaInvoices->range_31_60 ?? 0;
                    $inv['range_61_90'] = $gaInvoices->range_61_90 ?? 0;
                    $inv['range_gt90'] = $gaInvoices->range_gt90 ?? 0;

                    $total_no_due += $inv['no_due_days']; 
                    $total_range_15 += $inv['range_1_15']; 
                    $total_range_30 += $inv['range_16_30']; 
                    $total_range_60 += $inv['range_31_60']; 
                    $total_range_90 += $inv['range_61_90']; 
                    $total_range_gt90 += $inv['range_gt90']; 
                @endphp
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $ga->name }}</td>
                    @foreach([
                        'no_due_days' => '0',
                        'range_1_15' => '1-15',
                        'range_16_30' => '16-30',
                        'range_31_60' => '31-60',
                        'range_61_90' => '61-90',
                        'range_gt90'  => '90+'
                    ] as $field => $range)

                        <td class="text-end">
                            <a href="{{ url('reports/invoices/list') }}?{{ http_build_query([
                                'geo_area' => [$ga->id],
                                'range' => $range,
                                'invoice_type' => request()->invoice_type ?? [],
                                'status_id' => [\App\Enums\InvoiceStatus::NOT_PAID->value, \App\Enums\InvoiceStatus::PARTIALLY_PAID->value]
                                ]) }}" class="aging-link" target="_blank">{{ numberFormat($inv[$field],2) }}
                            </a>
                        </td>

                    @endforeach
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
                <th>{{ numberFormat($total_no_due,2) }}</th>
                <th>{{ numberFormat($total_range_15,2) }}</th>
                <th>{{ numberFormat($total_range_30,2) }}</th>
                <th>{{ numberFormat($total_range_60,2) }}</th>
                <th>{{ numberFormat($total_range_90,2) }}</th>
                <th>{{ numberFormat($total_range_gt90,2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@include('scripts.export-table', [
    'table' => 'aging-report',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'aging_report',
    'sheet'    => 'Report',
])