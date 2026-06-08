{{-- Invoice payment --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Payment for <span class="text-warning-emphasis">{{ $bill->invoice_number }}</span></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="add-old-invoice-lpc-success">
                <x-consumer.invoice-details :invoice="$bill" class="bg-info-subtle shadow-sm"/>
                <form action="{{ url('payments/oldPayment/generateLpc') }}" method="post" name="add-old-invoice-lpc-form" id="add-old-invoice-lpc-form">
                    @csrf
                    <div>
                        <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $bill->id }}">
                        <input type="hidden" name="invoice_balance" id="invoice_balance" value="{{ $bill->balance_amount }}">
                        <input type="hidden" name="till_paid_amount" id="till_paid_amount" value="{{ $bill->paid_amount }}">
                        @if ($lpc_exists)
                            <div class="alert alert-danger">There is already one LPC, can not generate another!</div>
                        @else
                            <div class="row mb-2">
                                <label for="notes" class="col-sm-4 col-form-label text-end">Notes&nbsp;:&nbsp;</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" name="notes" id="notes" placeholder="Enter notes"></textarea>
                                </div>
                            </div>
                            <div class="m-2" id="add-old-invoice-lpc-error"></div>
                            <div class="row">
                                <div class="offset-sm-4 col-sm-7">
                                    <button type="submit" class="btn btn-success"><i class="bi bi-check-square">&nbsp;</i>Generate LPC</button>
                                </div>
                            </div>
                        @endif
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

@include('scripts.ajax-form-submit', ['form' => 'add-old-invoice-lpc'])
@include('scripts.datepicker', ['list' => ['payment_date']])