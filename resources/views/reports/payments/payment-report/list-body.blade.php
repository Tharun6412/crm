<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
    <!-- LEFT SIDE FILTERS -->
    <div class="d-flex flex-wrap align-items-center gap-1">
        <!-- Search -->
        <div class="input-group w-auto">
            <span class="input-group-text">Search</span>
            <input type="text" name="key" id="key" class="form-control" value="{{ request()->key }}" placeholder="search key">
        </div>
        <div class="d-flex align-items-center flex-wrap gap-3">
            <div class="input-group w-auto">
                <span class="input-group-text">Date</span>
                <!-- From Date -->
                <input type="text" class="form-control" name="date_from" id="date_from" value="{{ request()->date_from }}" placeholder="DD-MM-YYYY">
                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                <!-- To Date -->
                <input type="text" class="form-control" name="date_to" id="date_to" value="{{ request()->date_to }}" placeholder="DD-MM-YYYY">
                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
            </div>
        </div>
        <!-- Submit -->
        <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        <!-- Reset -->
        <a href="{{ url('reports/paymentsReport') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        <!-- Record Count -->
        <span class="small text-muted">
          <span class="fw-semibold">({{ numberFormat($tRecords ?? 0) }})</span> Records found
        </span>
    </div>
    <div>
        {{-- <x-auth.link :href="url('reports/paymentsReport/paymentsReportExport') . '?' . request()->getQueryString()" class="btn btn-outline-info"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</x-auth.link> --}}
        {{ $payments->links('utils.cursor', ['modDiv' => 'payments-report-list']) }}
    </div>
</div>
@php
    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
    $i = 1; //(($payments->currentPage() - 1) * $payments->perPage())+1;
@endphp
<div class="table-responsive" style="min-height: 300px;">
    <table class="table table-bordered bg-white table-striped page-sort">
        <thead class="table-success align-middle">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th nowrap>
                    <a href="#">
                        Payment Code
                        @if ($sort_by == 'pay_invoice_payments.code')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap class="text-center">
                    <a href="#">
                        Payment Date
                        @if ($sort_by == 'pay_invoice_payments.payment_date')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="#">
                        Amount
                        @if ($sort_by == 'pay_invoice_payments.amount')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="#">
                        Payment Type
                        @if ($sort_by == 'pay_invoice_payments.payment_type_id')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="#">
                        Invoice No
                        @if ($sort_by == 'bil_invoices.invoice_number')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap class="text-center">
                    <a href="#">
                    Invoice Date
                        @if ($sort_by == 'bil_invoices.invoice_date')
                        <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <div class="d-flex">
                        <div><a href="#">
                        Invoice Type
                        @if ($sort_by == 'bil_invoices.type_id')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                        </a></div>
                        <x-master.invoice-type-filter class="float-end" />
                    </div>
                </th>
                <th nowrap>
                    <a href="#">
                    CRN
                    @if ($sort_by == 'cns_consumers.crn')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="#">
                    Consumer Name
                    @if ($sort_by == 'cns_consumers.fname')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                </th>
                <th nowrap>
                    <div class="d-flex">
                    <div>Segment</div>
                    {{-- <x-master.segment-filter class="float-end"  /> --}}
                    </div>
                </th>
                <th nowrap>
                    <div class="d-flex">
                        <div>Connection Type</div>
                        {{-- <x-master.connection-type-filter class="float-end"  /> --}}
                    </div>
                </th>
                <th nowrap><div class="d-flex"><div>GA</div><x-master.ga-filter class="float-end"  /></div></th>
            </tr>
        </thead>
        <tbody>
             @forelse ($payments as $pay)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $pay->code }}</td>
                    <td class="text-center">{{ dateFormat($pay->payment_date) }}</td>
                    <td class="text-end">{{ numberFormat($pay->amount,2) }}</td>
                    <td>{{ $pay->paymentType->name }}</td>
                    <td>
                        <a href="{{ url('bill/invoice/' . $pay->invoice->id) }}" target="_blank">{{ $pay->inv_number }} </a></td>
                    <td class="text-center">{{ dateFormat($pay->invoice->invoice_date) }}</td>
                    <td>{{ $pay->invoice->invoiceType->name }}</td>
                    <td><a href="{{ url('consumers/' . $pay->invoice->consumer->id) }}" target="_blank">{{ $pay->invoice->consumer->crn }}</a></td>
                    <td>{{ $pay->invoice->consumer->name }}</td>
                    <td>{{ $pay->invoice->consumer->segment->name }}</td>
                    <td>{{ $pay->invoice->consumer->connectType->name }}</td>
                    <td nowrap>{{ $pay->invoice->consumer->ga->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">No Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-info-subtle">
                <th class="text-end" colspan="3">Total</th>
                <th>{{ numberFormat($payments->sum('amount'),2) }}</th>
                <th colspan="9"></th>
            </tr>
        </tfoot>
    </table>
</div>
{{--  Reset pagination parameters for paginator --}}
@php
    // $payments->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
@endphp
<div>
    {{-- {{ $payments->links('utils.paginator', ['modDiv' => 'payments-report-list']) }} --}}
    {{ $payments->links('utils.cursor', ['modDiv' => 'payments-report-list']) }}
</div>
{{-- Scripts --}}
@include('scripts.datepicker', ['list' => ['date_from', 'date_to']])