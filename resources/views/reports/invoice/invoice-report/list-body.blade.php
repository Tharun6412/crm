<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <!-- LEFT SIDE FILTERS -->
    <div class="d-flex flex-wrap align-items-center gap-2">
        <!-- Search -->
        <div class="input-group w-auto">
            <span class="input-group-text">Search</span>
            <input type="text" name="key" id="key" class="form-control" value="{{ request()->key }}">
        </div>
        <!-- Range Dropdown -->
        @if (request()->has('range'))
            <select name="range" id="range" class="form-select w-auto">
                <option value="">All Days Range</option>
                @foreach([
                    '0',
                    '1-15',
                    '16-30',
                    '31-60',
                    '61-90',
                    '90+'
                ] as $range)
                    <option value="{{ $range }}"
                        @selected($range == request()->range)>
                        {{ $range }} Days
                    </option>
                @endforeach
            </select>
        @endif
        <!-- Calendar Toggle Button -->
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#invoiceDateFilter" aria-expanded="false"><i class="bi bi-calendar3"></i></button>
        <!-- Submit -->
        <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        <!-- Reset -->
        <a href="{{ url('reports/invoiceReport') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        <!-- Record Count -->
        <span class="small text-muted">
            <strong>({{ $invoices->total() }})</strong> Records found
        </span>
    </div>
    <div>
        <x-auth.link :href="url('reports/invoiceReport/invoicesReportExport') . '?' . request()->getQueryString()" class="btn btn-outline-info"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</x-auth.link>
    </div>
</div>
<!-- COLLAPSIBLE DATE FILTER -->
<div class="collapse {{ request()->filled('date_from') || request()->filled('date_to') ? 'show' : '' }} mt-2 mb-3" id="invoiceDateFilter">
    <div class="card border-info bg-info-subtle">
        <div class="card-body py-2 px-3">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span class="fw-semibold">
                    Invoice Date :
                </span>
                <!-- From Date -->
                <div class="input-group w-auto">
                    <span class="input-group-text">From</span>
                    <input type="text" class="form-control" name="date_from" id="date_from" value="{{ request()->date_from }}"><span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                </div>
                <!-- To Date -->
                <div class="input-group w-auto">
                    <span class="input-group-text">To</span>
                    <input type="text" class="form-control" name="date_to" id="date_to" value="{{ request()->date_to }}"><span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'bil_invoices.created_at';
    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
    $i = (($invoices->currentPage() - 1) * $invoices->perPage())+1;
@endphp
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover table-striped bg-white page-sort text-middle">
        <thead class="table-success align-middle">
            <tr class="bg-success-subtle">
                <th width="1%" nowrap>S No</th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'bil_invoices.invoice_number','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                        Invoice No
                        @if ($sort_by == 'bil_invoices.invoice_number')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                    </th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'bil_invoices.invoice_date','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                    Invoice Date
                        @if ($sort_by == 'bil_invoices.invoice_date')
                        <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <div class="d-flex">
                        <a href="{{ $invoices->appends(['sortBy' => 'bil_invoices.type_id','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">Invoice Type
                            @if ($sort_by == 'bil_invoices.type_id')
                                <i class="bi {{ $sort_icon }}"></i>
                            @endif
                        </a>
                        <x-master.invoice-type-filter />
                    </div>
                </th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'cns_consumers.crn','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                    CRN
                    @if ($sort_by == 'crn')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $invoices->appends(['sortBy' => 'cns_consumers.fname','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                    Consumer Name
                    @if ($sort_by == 'fname')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                </th>
                <th nowrap>
                    <div class="d-flex">
                        <div>Segment</div>
                        <div><x-master.segment-filter /></div>
                    </div>                    
                </th>
                <th nowrap>
                    <div class="d-flex">
                        <div>Connection Type</div>
                        <div><x-master.connection-type-filter /></div>
                    </div>
                </th>
                <th nowrap>
                    <div class="d-flex">
                        <div>GA</div>
                        <div class="float-end"><x-master.ga-filter /></div>
                    </div>
                </th>
                <th nowrap>District
                    @if (request()->has('geo_area'))
                        <x-master.district-filter class="float-end"/>
                    @endif
                </th>
                <th nowrap class="text-end">
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
                <th nowrap>
                    <div class="d-flex">
                        <a href="{{ $invoices->appends(['sortBy' => 'bil_invoices.status_id','sortOr' => $sort_order_inverse])->url($invoices->currentPage()) }}">
                            Payment Status
                            @if ($sort_by == 'bil_invoices.status_id')
                                <i class="bi {{ $sort_icon }}"></i>
                            @endif
                        </a>&nbsp;
                        @php
                            $inv_status = [1 => 'Paid', 2 => 'Not-Paid', 3 => 'Partial-Paid'];
                        @endphp
                        <x-admin.status-filter name="status_id" :data="$inv_status" />
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $inv)
                <tr class="align-middle">
                    <td class="text-center">{{ $i++ }}</td>
                    <td><a href="{{ url('bill/invoice/' . $inv->id) }}" target="_blank">&nbsp;{{ $inv->invoice_number }}</a></td>
                    <td>{{ dateFormat($inv->invoice_date) }}</td>
                    <td nowrap>{{ $inv->invoiceType->name }}</td>
                    <td><a href="{{ url('consumers/' . $inv->consumer_id) }}" target="_blank">&nbsp;{{ $inv->consumer->crn }}</a></td>
                    <td>{{ $inv->consumer->name }}</td>
                    <td>{{ $inv->consumer->segment->name }}</td>
                    <td>{{ $inv->consumer->connectType->name }}</td>
                    <td nowrap>{{ $inv->consumer->ga->name }}</td>
                    <td>{{ $inv->consumer->district->name }}</td>
                    <td class="text-end">{{ $inv->net_consumption ?? 0 }}</td>
                    <td>{{ dateFormat($inv->due_date) }}</td>
                    <td class="text-end">{{ numberFormat($inv->payable_amount, 2) }}</td>
                    <td class="text-end">{{ numberFormat($inv->balance_amount, 2) }}</td>
                    <td><x-invoice.status :status="$inv->status"/></td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" class="text-center">No Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="table-info">
                <th class="text-end" colspan="10">Total</th>
                <th class="text-end">{{ numberFormat($invoices->sum('net_consumption'),2) }}</th>
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
<div class="p-1 mb-2">
    {{ $invoices->links('utils.paginator', ['modDiv' => 'invoices-list']) }}
</div>
{{-- Scripts --}}
@push('scripts')
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@endpush