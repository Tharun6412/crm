<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text" id="key">Search</span>
                <input type="text" name="key" id="key" class="form-control" value="{{ request()->key }}">
            </div>
        </div>
        <div class="col-auto">
            <select name="connection_type_id" id="connection_type_id" class="form-select form-select-sm">
                <option value="">All Connection Types</option>
                <option value="{{ \App\Enums\ConnectionType::POSTPAID->value }}" @selected(\App\Enums\ConnectionType::POSTPAID->value == request()->connection_type_id)>Postpaid</option>
                <option value="{{ \App\Enums\ConnectionType::PREPAID->value }}" @selected(\App\Enums\ConnectionType::PREPAID->value == request()->connection_type_id)>Prepaid</option>
            </select>
        </div>
        <div class="col-auto">
            <select name="status_id" id="status_id" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="{{ \App\Enums\InvoiceStatus::PAID->value }}" @selected(\App\Enums\InvoiceStatus::PAID->value == request()->status_id)>Paid</option>
                <option value="{{ \App\Enums\InvoiceStatus::PARTIALLY_PAID->value }}" @selected(\App\Enums\InvoiceStatus::PARTIALLY_PAID->value == request()->status_id)>Partially paid</option>
                <option value="{{ \App\Enums\InvoiceStatus::NOT_PAID->value }}" @selected(\App\Enums\InvoiceStatus::NOT_PAID->value == request()->status_id)>Not paid</option>
            </select>
        </div>
        @if (request()->has('range'))
            <div class="col-auto">
                <select name="range" id="range" class="form-select form-select-sm">
                    <option value="">All Days Range</option>
                    @foreach([
                        'no_due_days' => '0',
                        'range_1_15' => '1-15',
                        'range_16_30' => '16-30',
                        'range_31_60' => '31-60',
                        'range_61_90' => '61-90',
                        'range_gt90'  => '90+'
                    ] as $field => $range)
                        <option value="{{ $range }}" @selected($range == request()->range)>{{ $range }} Days</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('reports/invoiceReport') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            ({{ $invoices->total() }}) Records found
        </div>
    </div>
    {{-- <div>
        <x-auth.link href="{{ url('reports/ageingReport/agingInvoicesExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary btn-sm"><i class="bi bi-plus-lg"></i>&nbsp;Export</x-auth.link>
    </div> --}}
</div>
@php
    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
    $i = (($invoices->currentPage() - 1) * $invoices->perPage())+1;
@endphp
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'invoice_number','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                        Invoice No
                        @if ($sort_by == 'invoice_number')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                    </th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'invoice_date','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                    Invoice Date
                        @if ($sort_by == 'invoice_date')
                        <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'type_id','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                    Invoice Type
                    @if ($sort_by == 'type_id')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                    <x-master.invoice-type-filter />
                </th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'crn','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                    CRN
                    @if ($sort_by == 'crn')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'fname','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                    Consumer Name
                    @if ($sort_by == 'fname')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                </th>
                <th nowrap>Segment<x-master.segment-filter /></th>
                <th nowrap>GA<x-master.ga-filter /></th>
                <th nowrap>District
                    @if (request()->has('geo_area'))
                        <x-master.district-filter class="float-end"/>
                    @endif
                </th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'net_consumption','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                        Consumption
                        @if ($sort_by == 'net_consumption')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'due_date','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                        Due Date
                        @if ($sort_by == 'due_date')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'payable_amount','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                        Invoice Amount
                        @if ($sort_by == 'payable_amount')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'balance_amount','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                        Balance Amount
                        @if ($sort_by == 'balance_amount')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'bil_invoices.status_id','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                        Payment Status
                        @if ($sort_by == 'bil_invoices.status_id')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                    {{-- @php
                        $inv_status = [1 => 'Paid', 2 => 'Not-Paid', 3 => 'Partial-Paid'];
                    @endphp
                    <x-admin.status-filter name="status_id" :data="$inv_status" /> --}}
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = (($invoices->currentPage() - 1) * $invoices->perPage())+1;
            @endphp
            @forelse($invoices as $inv)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td><a href="{{ url('bill/invoice/' . $inv->id) }}" target="_blank">&nbsp;{{ $inv->invoice_number }}</a></td>
                    <td>{{ dateFormat($inv->invoice_date) }}</td>
                    <td>{{ $inv->invoiceType->name }}</td>
                    <td><a href="{{ url('consumers/' . $inv->consumer_id) }}" target="_blank">&nbsp;{{ $inv->consumer->crn }}</a></td>
                    <td>{{ $inv->consumer->name }}</td>
                    <td>{{ $inv->consumer->segment->name }}</td>
                    <td>{{ $inv->consumer->ga->name }}</td>
                    <td>{{ $inv->consumer->district->name }}</td>
                    <td>{{ $inv->net_consumption ?? 0 }}</td>
                    <td>{{ dateFormat($inv->due_date) }}</td>
                    <td class="text-end">{{ numberFormat($inv->payable_amount, 2) }}</td>
                    <td class="text-end">{{ numberFormat($inv->balance_amount, 2) }}</td>
                    <td><x-invoice.status :status="$inv->status"/></td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center">No Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end" colspan="9">Total</th>
                <th>{{ numberFormat($invoices->sum('net_consumption'),2) }}</th>
                <th></th>
                <th class="text-end">{{ numberFormat($invoices->sum('payable_amount'),2) }}</th>
                <th class="text-end">{{ numberFormat($invoices->sum('balance_amount'),2) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
{{--  Reset pagination parameters for paginator --}}
@php
    $invoices->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
@endphp
<div>
    {{ $invoices->links('utils.paginator', ['modDiv' => 'invoices-list']) }}
</div>
