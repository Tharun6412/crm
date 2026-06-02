{{-- Invoice payment --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Payment for <span class="text-warning-emphasis">{{ $bill->invoice_number }}</span></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="add-old-invoice-payment-success">
                <x-consumer.invoice-details :invoice="$bill" class="bg-info-subtle shadow-sm"/>
                <form action="{{ url('payments/oldPayment/'.$bill->id) }}" method="post" name="add-old-invoice-payment-form" id="add-old-invoice-payment-form">
                    @csrf
                    @method('PUT')
                    <div>
                        <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $bill->id }}">
                        <input type="hidden" name="invoice_balance" id="invoice_balance" value="{{ $bill->balance_amount }}">
                        <input type="hidden" name="till_paid_amount" id="till_paid_amount" value="{{ $bill->paid_amount }}">
                        <div class="row mb-2">
                            <label for="payment_date" class="col-sm-4 col-form-label text-end">Payment Date&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="payment_date" name="payment_date" placeholder="DD-MM-YYYY">
                                    <span class="input-group-text"><i class="bi-calendar3"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="payment_type" class="col-sm-4 col-form-label text-end">Payment Type&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
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
                            <label for="amount" class="col-sm-4 col-form-label text-end">Amount&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control text-end" id="amount" name="amount" placeholder="Enter the amount to be paid.">
                                    <span class="input-group-text"><i class="bi-currency-rupee"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="transaction_no" class="col-sm-4 col-form-label text-end">Transaction No/Cheque no&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="transaction_no" name="transaction_no" placeholder="Enter the transaction number.">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="notes" class="col-sm-4 col-form-label text-end">Notes&nbsp;:&nbsp;</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" name="notes" id="notes" placeholder="Enter notes"></textarea>
                            </div>
                        </div>
                        <div class="m-2" id="add-old-invoice-payment-error"></div>
                        <div class="row">
                            <div class="offset-sm-4 col-sm-7">
                                <button type="submit" class="btn btn-success"><i class="bi bi-check-square">&nbsp;</i>Confirm Payment</button>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- <div class="row mb-2">
                    <label class="col-sm-4 col-form-label text-end">Paid Amount :</label>
                    <label class="col-sm-4 col-form-label">{{ numberFormat($bill->paid_amount, 2) }}</label>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label text-end">Credt / Debit Amount :</label>
                    <label class="col-sm-4 col-form-label">{{ numberFormat($bill->credit_amount, 2) }}</label>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-4 col-form-label text-end">Balance Amount :</label>
                    <label class="col-sm-4 col-form-label">{{ numberFormat($bill->balance_amount, 2) }}</label>
                </div>
                @if ($bill->balance_amount > 0)
                    
                @endif --}}
                {{-- @if ($bill->payments->count() > 0)
                    <h4>Payment records</h4>
                    <div class="responsive mt-2">
                        <table class="table table-bordered table-hover table-info">
                            <thead class="table-info">
                                <tr>
                                    <th width="1%" nowrap="">S No</th>
                                    <th>#Ref</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Amount</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bill->payments as $pay)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pay->code }}</td>
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
                @endif --}}
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>

@include('scripts.ajax-form-submit', ['form' => 'add-old-invoice-payment'])
@include('scripts.datepicker', ['list' => ['payment_date']])