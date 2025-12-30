{{-- Gas invoice payments --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Payment for "{{ $bill->invoice_number }}"</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="add-gas-payment-success">
                <x-consumer.invoice-details :invoice="$bill" class="bg-info-subtle"/>
                @if ($bill->balance_amount > 0)
                    <form action="{{ url('payments/gasPayments/') }}" method="post" name="add-gas-payment-form" id="add-gas-payment-form">
                        @csrf
                        <h4>Payment details</h4>
                        <div>
                            <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $bill->id }}">
                            <input type="hidden" name="invoice_balance" id="invoice_balance" value="{{ $bill->balance_amount }}">
                            <input type="hidden" name="till_paid_amount" id="till_paid_amount" value="{{ $bill->paid_amount }}">
                            <div class="row mb-2">
                                <label for="payment_type" class="col-sm-3 col-form-label text-end">Payment Type&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
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
                                <label for="transaction_no" class="col-sm-3 col-form-label text-end">Transaction No/ Cheque no&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="transaction_no" name="transaction_no" placeholder="Enter the transaction number.">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="amount" class="col-sm-3 col-form-label text-end">Amount&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-end" id="amount" name="amount" placeholder="Enter the amount to be paid." value="{{ $bill->balance_amount }}">
                                        <span class="input-group-text"><i class="bi-currency-rupee"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="notes" class="col-sm-3 col-form-label text-end">Notes&nbsp;:&nbsp;</label>
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
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>

@include('scripts.ajax-form-submit', ['form' => 'add-gas-payment'])