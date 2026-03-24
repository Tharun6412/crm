<form name="aging-reports-search-form" id="aging-reports-search-form"  action="{{ url('reports/ageingReport') }}" method="get">
    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
        <!-- Invoice Type Filter Card -->
        <div>
            <div class="form-control">
                <span class="fw-semibold">Invoice Type</span>
                <div class="w-auto float-end"><x-master.invoice-type-filter /></div>
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
            <!-- Reset -->
            <a href="{{ url('reports/ageingReport') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-bordered table-hover page-sort table-striped bg-white">
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
            @endphp
            @forelse($gasAging as $ga)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $ga->ga_name }}</td>
                    @foreach([
                        'no_due_days' => '0',
                        'range_1_15' => '1-15',
                        'range_16_30' => '16-30',
                        'range_31_60' => '31-60',
                        'range_61_90' => '61-90',
                        'range_gt90'  => '90+'
                    ] as $field => $range)

                        <td class="text-end">
                            <a href="{{ url('reports/invoiceReport') }}?{{ http_build_query([
                                'geo_area' => [$ga->ga_id],
                                'range' => $range,
                                'invoice_type' => request()->invoice_type ?? [],
                                'status_id' => [\App\Enums\InvoiceStatus::NOT_PAID->value, \App\Enums\InvoiceStatus::PARTIALLY_PAID->value]
                                ]) }}" class="aging-link" target="_blank">{{ numberFormat($ga->$field,2) }}
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
                <th>{{ numberFormat($gasAging->sum('no_due_days'),2) }}</th>
                <th>{{ numberFormat($gasAging->sum('range_1_15'),2) }}</th>
                <th>{{ numberFormat($gasAging->sum('range_16_30'),2) }}</th>
                <th>{{ numberFormat($gasAging->sum('range_31_60'),2) }}</th>
                <th>{{ numberFormat($gasAging->sum('range_61_90'),2) }}</th>
                <th>{{ numberFormat($gasAging->sum('range_gt90'),2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>