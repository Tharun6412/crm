<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <!-- LEFT SIDE FILTERS -->
    <div class="d-flex flex-wrap align-items-center gap-2">
        <!-- Search -->
        <div class="input-group input-group-sm w-auto">
            <span class="input-group-text">Search</span>
            <input type="text" name="key" id="key" class="form-control" value="{{ request()->key }}">
        </div>
        <div class="d-flex align-items-center flex-wrap gap-3">
            <div class="input-group input-group-sm w-auto">
                <span class="input-group-text">Payment Date</span>
                <!-- From Date -->
                <input type="text" class="form-control" name="date_from" id="date_from" value="{{ request()->date_from }}"><span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                <!-- To Date -->
                <input type="text" class="form-control" name="date_to" id="date_to" value="{{ request()->date_to }}"><span class="input-group-text"><i class="bi bi-calendar3"></i></span>
            </div>
        </div>
        <!-- Submit -->
        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-search"></i></button>
        <!-- Reset -->
        <a href="{{ url('reports/paymentsReport') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
        <!-- Record Count -->
        <span class="small text-muted">
            ({{ $payments->total() }}) Records found
        </span>
    </div>
    {{-- <div>
        <x-auth.link :href="url('reports/invoiceReport/invoicesReportExport') . '?' . request()->getQueryString()" class="btn btn-secondary btn-sm"><i class="bi bi-plus-lg"></i>&nbsp;Export</x-auth.link>
    </div> --}}
</div>
@php
    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
    $i = (($payments->currentPage() - 1) * $payments->perPage())+1;
@endphp
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th nowrap>
                    <a href="{{ $payments->appends(['sortBy' => 'pay_invoice_payments.code','sortOr' => $sort_order_inverse])->url($payments->currentPage()) }}">
                        Payment Code
                        @if ($sort_by == 'pay_invoice_payments.code')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $payments->appends(['sortBy' => 'pay_invoice_payments.payment_date','sortOr' => $sort_order_inverse])->url($payments->currentPage()) }}">
                        Payment Date
                        @if ($sort_by == 'pay_invoice_payments.payment_date')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $payments->appends(['sortBy' => 'pay_invoice_payments.amount','sortOr' => $sort_order_inverse])->url($payments->currentPage()) }}">
                        Amount
                        @if ($sort_by == 'pay_invoice_payments.amount')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $payments->appends(['sortBy' => 'pay_invoice_payments.payment_type_id','sortOr' => $sort_order_inverse])->url($payments->currentPage()) }}">
                        Payment Type
                        @if ($sort_by == 'pay_invoice_payments.payment_type_id')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $payments->appends(['sortBy' => 'bil_invoices.invoice_number','sortOr' => $sort_order_inverse])->url($payments->currentPage()) }}">
                        Invoice No
                        @if ($sort_by == 'bil_invoices.invoice_number')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $payments->appends(['sortBy' => 'bil_invoices.invoice_date','sortOr' => $sort_order_inverse])->url($payments->currentPage()) }}">
                    Invoice Date
                        @if ($sort_by == 'bil_invoices.invoice_date')
                        <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $payments->appends(['sortBy' => 'bil_invoices.type_id','sortOr' => $sort_order_inverse])->url($payments->currentPage()) }}">
                    Invoice Type
                    @if ($sort_by == 'bil_invoices.type_id')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                    <x-master.invoice-type-filter class="float-end" />
                </th>
                <th nowrap>
                    <a href="{{ $payments->appends(['sortBy' => 'cns_consumers.crn','sortOr' => $sort_order_inverse])->url($payments->currentPage()) }}">
                    CRN
                    @if ($sort_by == 'cns_consumers.crn')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $payments->appends(['sortBy' => 'cns_consumers.fname','sortOr' => $sort_order_inverse])->url($payments->currentPage()) }}">
                    Consumer Name
                    @if ($sort_by == 'cns_consumers.fname')
                        <i class="bi {{ $sort_icon }}"></i>
                    @endif
                    </a>
                </th>
                <th nowrap>Segment<x-master.segment-filter class="float-end"  /></th>
                <th nowrap>Connection Type<x-master.connection-type-filter class="float-end"  /></th>
                <th nowrap>GA<x-master.ga-filter class="float-end"  /></th>
            </tr>
        </thead>
        <tbody>
             @forelse ($payments as $pay)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $pay->code }}</td>
                    <td>{{ dateFormat($pay->payment_date) }}</td>
                    <td>{{ numberFormat($pay->amount,2) }}</td>
                    <td>{{ $pay->paymentType->name }}</td>
                    <td>
                        <a href="{{ url('bill/invoice/' . $pay->invoice->id) }}" target="_blank">{{ $pay->inv_number }} </a></td>
                    <td>{{ dateFormat($pay->invoice->invoice_date) }}</td>
                    <td>{{ $pay->invoice->invoiceType->name }}</td>
                    <td>{{ $pay->invoice->consumer->crn }}</td>
                    <td>{{ $pay->invoice->consumer->name }}</td>
                    <td>{{ $pay->invoice->consumer->segment->name }}</td>
                    <td>{{ $pay->invoice->consumer->connectType->name }}</td>
                    <td>{{ $pay->invoice->consumer->ga->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">No Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end" colspan="3">Total</th>
                <th>{{ numberFormat($payments->sum('amount'),2) }}</th>
                <th colspan="9"></th>
            </tr>
        </tfoot>
    </table>
</div>
{{--  Reset pagination parameters for paginator --}}
@php
    $payments->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
@endphp
<div>
    {{ $payments->links('utils.paginator', ['modDiv' => 'payments-report-list']) }}
</div>
{{-- Scripts --}}
@push('scripts')
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@endpush