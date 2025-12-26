<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Payment</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="add-gas-payment-success">
                <x-consumer.invoice-details :invoice="$bill"/>
                @if ($bill->balance_amount > 0)
                    <form action="{{ url('payments/gasPayments/') }}" method="post" name="add-gas-payment-form" id="add-gas-payment-form">
                        @csrf
                        <div>
                            <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $bill->id }}">
                            <input type="hidden" name="invoice_balance" id="invoice_balance" value="{{ $bill->balance_amount }}">
                            <input type="hidden" name="till_paid_amount" id="till_paid_amount" value="{{ $bill->paid_amount }}">
                            <div class="mb-3 row">
                                <label for="payment_type" class="col-sm-4 col-form-label">Payment Type&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="payment_type" id="payment_type">
                                        <option value="">Select</option>
                                        @foreach ($payment_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="transaction_no" class="col-sm-4 col-form-label">Transaction No/Cheque no&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="transaction_no" name="transaction_no" placeholder="Enter the transaction number.">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount" class="col-sm-4 col-form-label">Amount&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter the amount to be paid." value="{{ $bill->balance_amount }}">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="notes" class="col-sm-4 col-form-label">Notes&nbsp;:&nbsp;</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="notes" id="notes" placeholder="Enter notes"></textarea>
                                </div>
                            </div>
                            <div id="add-gas-payment-error"></div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-success"><i class="bi bi-check-square">&nbsp;</i>Pay</button>
                        </div>
                    </form>
                @endif
                @if ($bill->payments->count() > 0)
                    <div class="bg-info-subtle rounded mt-3">
                        <div class="responsive">
                            <table class="table table-bordered table-hover table-primary">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="1%" nowrap="">S No</th>
                                        <th>Payment Date</th>
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
                                            <td class="text-end">{{ numberFormat($pay->amount) }}</td>
                                            <td><span>{{ $pay->status->name }}</span></td>
                                            <td>{{ $pay->createdBy->emp_id }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>

@include('scripts.ajax-form-submit', ['form' => 'add-gas-payment'])