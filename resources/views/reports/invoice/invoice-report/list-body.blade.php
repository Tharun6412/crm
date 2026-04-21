@php
    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'bil_invoices.created_at';
    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
    // $i = (($invoices->currentPage() - 1) * $invoices->perPage())+1;

    // Helper to build sort URL (cursor pagination doesn't use page numbers)
    $sortUrl = fn($column) => request()->fullUrlWithQuery([
        'sortBy' => $column,
        'sortOr' => ($sort_by === $column) ? $sort_order_inverse : 'asc',
        'cursor'  => null, // reset cursor on sort change
    ]);
@endphp
<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <!-- LEFT SIDE FILTERS -->
    <div class="d-flex flex-wrap align-items-center gap-2">
        <!-- Search -->
        <div class="input-group w-auto">
            <span class="input-group-text">Search</span>
            <input type="text" name="key" id="key" class="form-control" value="{{ request()->key }}">
        </div>
        <!-- Calendar Toggle Button -->
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#invoiceDateFilter" aria-expanded="false"><i class="bi bi-calendar3"></i></button>
        <!-- Submit -->
        <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        <!-- Reset -->
        <a href="{{ url('reports/invoices/report') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        <!-- Record Count -->
        <span class="small text-muted">
            <strong>({{ numberFormat($tRecords ?? 0) }})</strong> Records found
        </span>
    </div>
    {{-- <div>
        <x-auth.link :href="url('reports/invoiceReport/invoicesReportExport') . '?' . request()->getQueryString()" class="btn btn-outline-info"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</x-auth.link>
    </div> --}}
    <div>
        {{ $invoices->links('utils.cursor', ['modDiv' => 'invoices-list']) }}
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
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover table-striped bg-white page-sort text-middle">
        <thead class="table-success align-middle">
            <tr class="bg-success-subtle">
                <th width="1%" nowrap>S No</th>
                <th nowrap>
                     <a href="{{ $sortUrl('bil_invoices.invoice_number') }}">
                        Invoice No
                        @if ($sort_by == 'bil_invoices.invoice_number')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                    </th>
                <th nowrap>
                     <a href="{{ $sortUrl('bil_invoices.invoice_date') }}">
                    Invoice Date
                        @if ($sort_by == 'bil_invoices.invoice_date')
                        <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <div class="d-flex">
                         <a href="{{ $sortUrl('bil_invoices.type_id') }}">
                            Invoice Type
                            @if ($sort_by == 'bil_invoices.type_id')
                                <i class="bi {{ $sort_icon }}"></i>
                            @endif
                        </a>
                        <x-master.invoice-type-filter />
                    </div>
                </th>
                <th nowrap>
                    <a href="{{ $sortUrl('consumer.crn') }}">
                    CRN
                    @if ($sort_by == 'consumer.crn')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $sortUrl('fname') }}">
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
                    <a href="{{ $sortUrl('net_consumption') }}">
                        Consumption
                        @if ($sort_by == 'net_consumption')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                     <a href="{{ $sortUrl('due_date') }}">
                        Due Date
                        @if ($sort_by == 'due_date')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $sortUrl('taxable_amount') }}">
                        Base Amount
                        @if ($sort_by == 'taxable_amount')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $sortUrl('tax_amount') }}">
                        Tax Amount
                        @if ($sort_by == 'tax_amount')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $sortUrl('total_amount') }}">
                        Invoice Amount
                        @if ($sort_by == 'total_amount')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $sortUrl('payable_amount') }}">
                        Payable Amount
                        @if ($sort_by == 'payable_amount')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-end" nowrap>
                    <a href="{{ $sortUrl('balance_amount') }}">
                        Balance Amount
                        @if ($sort_by == 'balance_amount')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <div class="d-flex">
                        <a href="{{ $sortUrl('bil_invoices.status_id') }}">
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
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><a href="{{ url('bill/invoice/' . $inv->id) }}" target="_blank">&nbsp;{{ $inv->invoice_number }}</a></td>
                    <td>{{ dateFormat($inv->invoice_date) }}</td>
                    <td nowrap>{{ $inv->invoiceType->name }}</td>
                    <td><a href="{{ url('consumers/' . $inv->consumer_id) }}" target="_blank">&nbsp;{{ $inv->consumer->crn }}</a></td>
                    <td>{{ $inv->consumer->name }}</td>
                    <td>{{ $inv->consumer->segment->name }}</td>
                    <td>{{ $inv->consumer->connectType->name }}</td>
                    <td nowrap>{{ $inv->consumer->ga->name }}</td>
                    <td>{{ $inv->consumer->district->name }}</td>
                    <td class="text-end">{{ $inv->consumption->net_consumption ?? 0 }}</td>
                    <td>{{ dateFormat($inv->due_date) }}</td>
                    <td class="text-end">{{ numberFormat($inv->taxable_amount, 2) }}</td>
                    <td class="text-end">{{ numberFormat($inv->tax_amount, 2) }}</td>
                    <td class="text-end">{{ numberFormat($inv->total_amount, 2) }}</td>
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
                <th class="text-end">{{ numberFormat($pageTotals['net_consumption'],2) }}</th>
                <th></th>
                <th class="text-end">{{ numberFormat($pageTotals['taxable_amount'],2) }}</th>
                <th class="text-end">{{ numberFormat($pageTotals['tax_amount'],2) }}</th>
                <th class="text-end">{{ numberFormat($pageTotals['total_amount'],2) }}</th>
                <th class="text-end">{{ numberFormat($pageTotals['payable_amount'],2) }}</th>
                <th class="text-end">{{ numberFormat($pageTotals['balance_amount'],2) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
<div class="row justify-content-end">
    <div class="card text-bg-primary mb-3" style="max-width: 15rem;">
      <div class="card-body">
        <h4 class="card-title">Total Consumption</h4>
        <p class="card-text">0.00</p>
      </div>
    </div>
    <div class="card text-bg-secondary mb-3" style="max-width: 15rem;">
      <div class="card-body">
        <h4 class="card-title">Total Base Amount</h4>
        <p class="card-text">{{ numberFormat($totals['total_taxable'],2) }}</p>
      </div>
    </div>
    <div class="card text-bg-success mb-3" style="max-width: 15rem;">
      <div class="card-body">
        <h4 class="card-title">Total Tax Amount</h4>
        <p class="card-text">{{ numberFormat($totals['total_tax'],2) }}</p>
      </div>
    </div>
    <div class="card text-bg-danger mb-3" style="max-width: 15rem;">
      <div class="card-body">
        <h4 class="card-title">Total Invoice Amount</h4>
        <p class="card-text">{{ numberFormat($totals['total_amount'],2) }}</p>
      </div>
    </div>
    <div class="card text-bg-warning mb-3" style="max-width: 15rem;">
      <div class="card-body">
        <h4 class="card-title">Total Payable Amount</h4>
        <p class="card-text">{{ numberFormat($totals['total_payable'],2) }}</p>
      </div>
    </div>
    <div class="card text-bg-info mb-3" style="max-width: 15rem;">
      <div class="card-body">
        <h4 class="card-title">Total Balance</h4>
        <p class="card-text">{{ numberFormat($totals['total_balance'],2) }}</p>
      </div>
    </div>
</div>
<div class="p-1 mb-2">
    {{ $invoices->links('utils.cursor', ['modDiv' => 'invoices-list']) }}
</div>
{{-- Scripts --}}
@push('scripts')
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@endpush