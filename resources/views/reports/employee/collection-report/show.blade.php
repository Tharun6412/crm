{{-- Employee collection show details --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Employee Collection Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @if ($invoice_payments->count() > 0 OR $sd_payments->count() > 0)
                @php
                    $li = 1;
                @endphp
                <div class="d-flex justify-content-between pb-2">
                    <div class="fs-5">
                        {{ $employee->emp_id ?? '' }}, {{ $employee->name ?? '' }}&nbsp;<i class="bi bi-three-dots-vertical text-danger"></i>&nbsp
                        {{ $date_from }} to {{ $date_to }}&nbsp;<i class="bi bi-three-dots-vertical text-danger"></i>&nbsp
                        {{ numberFormat($invoice_payments->count() + $sd_payments->count()) }} Records
                    </div>
                    <div><button type="button" class="btn btn-outline-info" id="exportClnBtn"><i class="bi bi-file-earmark-arrow-down"></i>&nbsp;Export</button></div>
                </div>
                <table class="table table-bordered table-hover table-warning fs-sm" id="emp-clcn-dtls">
                    <thead class="table-warning">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Colleded At</th>
                            <th>CRN</th>
                            <th>Type</th>
                            <th>Invoice No</th>
                            <th>Payment Mode</th>
                            <th>Reference No</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice_payments as $payment)
                            <tr>
                                <td>{{ $li++ }}</td>
                                <td nowrap>{{ $payment->created_at?->format('d-m-Y H:i') }}</td>
                                <td>{{ $payment->invoice->consumer->crn ?? ''}}</td>
                                <td>{{ $payment->invoice->invoiceType->name ?? '' }}</td>
                                <td>{{ $payment->invoice->invoice_number ?? '' }}</td>
                                <td>{{ $payment->paymentType->name ?? '' }}</td>
                                <td>{{ $payment->transaction_id ?? '' }}</td>
                                <td class="text-end">{{ numberFormat($payment->amount ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                        @foreach ($sd_payments as $payment)
                            <tr>
                                <td>{{ $li++ }}</td>
                                <td>{{ $payment->created_at?->format('d-m-Y H:i') }}</td>
                                <td>{{ $payment->consumer->crn ?? ''}}</td>
                                <td>Security Deposit</td>
                                <td></td>
                                <td>{{ $payment->paymentType->name ?? '' }}</td>
                                <td>{{ $payment->transaction_number ?? '' }}</td>
                                <td class="text-end">{{ numberFormat($payment->amount ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="fw-semibold">
                        <tr>
                            <td colspan="7" class="text-end">Total Collection</td>
                            <td class="text-end">{{ numberFormat($invoice_payments->sum('amount') + $sd_payments->sum('amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="alert alert-warning">
                    No records found!
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.export-table', [
    'table' => 'emp-clcn-dtls',
    'button' => 'exportClnBtn',
    'tabBased' => false,
    'filename' => 'employee-collection-details',
    'sheet'    => 'Report',
])