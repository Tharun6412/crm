@php
    use Carbon\Carbon;
@endphp
{{-- Gas invoice payments --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Payment for "{{ $bill->invoice_number }}"</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="add-gas-payment-success">
                <x-consumer.invoice-details :invoice="$bill" class="bg-info-subtle p-2"/>
                {{-- Get connected invoices --}}
                @php
                    $child_inv_balance = 0;
                @endphp
                @if ($bill->childInvoices->count() > 0)
                    <h4>Connected Invoices ({{ $bill->childInvoices->count() }})</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-primary">
                            <thead class="table-primary">
                                <tr>
                                    <th width="1%" nowrap>S No</th>
                                    <th>Invoice No</th>
                                    <th>Type</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Balnce</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bill->childInvoices as $invoice_item)
                                    @php
                                        $child_inv_balance += $invoice_item->balance_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $invoice_item->invoice_number }}</td>
                                        <td>{{ $invoice_item->invoiceType->name ?? '' }}</td>
                                        <td class="text-end">{{ numberFormat($invoice_item->total_amount, 2) }}</td>
                                        <td class="text-end">{{ numberFormat($invoice_item->balance_amount, 2) }}</td>
                                        <td>{{ $invoice_item->status->name ?? '' }}</td>
                                        <td>
                                            <a href="{{ url('bill/invoice/' . $invoice_item->id) }}" target="_blank">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-semibold">
                                    <td colspan="4" class="text-end">Total</td>
                                    <td class="text-end"> {{ numberFormat($child_inv_balance, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
                {{-- Payment form --}}
                @if ($bill->balance_amount > 0)
                    @php
                        $lpc_applicable = $late_fee = 0;
                        if (now()->gt(Carbon::parse($bill->due_date)->endOfDay()) and !$lpc_applied) {
                            $lpc_applicable = 1;
                            switch ($bill->consumer->segment_id) {
                                case '1':
                                    $late_fee = 20;
                                    break;
                                case '2':
                                    $late_fee = 50;
                                    break;
                                case '3':
                                    $late_fee = 100;
                                    break;
                                default:
                                    $late_fee = 0;
                                    break;
                            }
                        }
                    @endphp
                    <form action="{{ url('payments/gasPayments/') }}" method="post" name="add-gas-payment-form" id="add-gas-payment-form">
                        @csrf
                        <h4>Payment details</h4>
                        <div>
                            <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $bill->id }}">
                            <input type="hidden" name="invoice_balance" id="invoice_balance" value="{{ ($late_fee + $child_inv_balance + $bill->balance_amount) }}">
                            <input type="hidden" name="till_paid_amount" id="till_paid_amount" value="{{ $bill->paid_amount }}">
                            <input type="hidden" name="lpc_applicable" id="lpc_applicable" value="{{ $lpc_applicable }}">
                            @if ($lpc_applicable)
                                <input type="hidden" name="late_fee" id="late_fee" value="{{ $late_fee }}">
                                <div class="row mb-2">
                                    <label class="col-sm-4 col-form-label text-end">Late Fee :</label>
                                    <label class="col-sm-3 col-form-label">{{ numberFormat(($late_fee), 2) }}</label>
                                </div>    
                            @endif
                            <div class="row mb-2">
                                <label class="col-sm-4 col-form-label text-end">Total Payable Amount:</label>
                                <label class="col-sm-3 col-form-label">{{ numberFormat(($late_fee + $child_inv_balance + $bill->balance_amount), 2) }}</label>
                            </div>
                            <div class="row mb-2">
                                <label for="payment_type" class="col-sm-4 col-form-label text-end">Payment Type<i class="text-danger">*&nbsp;</i>&nbsp;:&nbsp;</label>
                                <div class="col-sm-7">
                                    <select class="form-select" name="payment_type" id="payment_type">
                                        <option value="">Select</option>
                                        @foreach ($payment_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="transaction_no" class="col-sm-4 col-form-label text-end">Transaction No/ Cheque no<i class="text-danger">*&nbsp;</i>&nbsp;:&nbsp;</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="transaction_no" name="transaction_no" placeholder="Enter the transaction number.">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="amount" class="col-sm-4 col-form-label text-end">Amount<i class="text-danger">*&nbsp;</i>&nbsp;:&nbsp;</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-end" id="amount" name="amount" placeholder="Enter the amount to be paid." value="{{ ($late_fee + $child_inv_balance + $bill->balance_amount) }}">
                                        <span class="input-group-text"><i class="bi-currency-rupee"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="notes" class="col-sm-4 col-form-label text-end">Notes&nbsp;:&nbsp;</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" name="notes" id="notes" placeholder="Enter notes"></textarea>
                                </div>
                            </div>
                            <div class="m-2" id="add-gas-payment-error"></div>
                        </div>
                        <div class="row">
                            <div class="offset-sm-3 col-sm-7">
                                <button type="submit" class="btn btn-success"><i class="bi bi-cash">&nbsp;</i>Confirm Payment</button>
                            </div>
                        </div>
                    </form>
                @endif
                {{-- Payments --}}
                @if ($bill->payments->count() > 0)
                    <h4>Payments</h4>
                    <div class="responsive">
                        <table class="table table-bordered table-hover table-primary">
                            <thead class="table-primary">
                                <tr>
                                    <th width="1%" nowrap="">S No</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th class="text-end">Amount</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bill->payments as $pay)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pay->payment_date?->format('d-m-y') }}</td>
                                        <td>{{ $pay->paymentType->name ?? '' }}</td>
                                        <td class="text-end">{{ numberFormat($pay->amount, 2) }}</td>
                                        <td><span>{{ $pay->status->name }}</span></td>
                                        <td>{{ $pay->createdBy->emp_id }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
{{-- Script --}}
@include('scripts.ajax-form-submit', ['form' => 'add-gas-payment'])