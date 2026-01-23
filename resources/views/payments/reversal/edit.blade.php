<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Payment Reversal&nbsp;#{{ $payment->invoice->invoice_number }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="reversal-success">
                <form id="reversal-form" action="{{ url('payments/reversalPaymentUpdate/'.$payment->id) }}">
                    @csrf
                    @method('PUT')
                    {{-- Invoice and consumer Details --}}
                    <x-consumer.invoice-details :invoice="$payment->invoice"/>
                    {{-- Payment Details --}}
                    <div class="row g-2 pb-2 my-2 bg-info-subtle rounded">
                        <div class="col-sm-2 text-end fw-semibold text-nowrap">Code :</div>
                        <div class="col-sm-4">{{ $payment->code }}</div>
                        <div class="col-sm-2 text-end fw-semibold text-nowrap">Transaction Number :</div>
                        <div class="col-sm-4">{{ $payment->transaction_id }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Amount : </div>
                        <div class="col-sm-4">{{ numberFormat($payment->amount, 2) }}</div>
                        <div class="col-sm-2 text-end fw-semibold text-nowrap">Payment Date :</div>
                        <div class="col-sm-4">{{ $payment->payment_date?->format('d-m-Y') }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Status : </div>
                        <div class="col-sm-4">{{ $payment->status->name }}</div>
                        <div class="col-sm-2 text-end fw-semibold text-nowrap">Payment Type :</div>
                        <div class="col-sm-4">{{ $payment->paymentType->name }}</div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger validate-err-msg" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="mb-3" id="reversal-error"></div>
                    <div class="row mb-2">
                        <div class="col-md-12 col-sm-12">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Payment Reversal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-file-submit', ['form' => 'reversal'])
