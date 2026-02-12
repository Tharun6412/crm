{{-- Transaction method --}}
@php
    // Transaction Status Update 
    use \App\Enums\TransactionStatus;
    $updatedStatus = in_array($transaction->transaction_status_id, [3,4]) ? TransactionStatus::SUCCESS : TransactionStatus::FAIL;
@endphp
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Transaction Details - {{ $transaction->module->name }} - {{ $transaction->transaction_id }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="update-transaction-success">
                <form method="POST" id="update-transaction-form" action={{ url('payments/transactions/'.$transaction->id) }}>
                    @csrf
                    @method('PUT')
                    <div class="alert alert-warning"> 
                        <x-consumer.basic-details :consumer="$transaction->consumer"/>
                    </div>
                    <div class="row g-2 mb-2 alert alert-info">
                        <div class="col-sm-3 text-end fw-semibold">Payment Module : </div>
                        <div class="col-sm-3">{{ $transaction->module->name }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Transaction Date : </div>
                        <div class="col-sm-3">{{ $transaction->transaction_date->format('d-m-Y') }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Amount : </div>
                        <div class="col-sm-3">{{ numberFormat($transaction->amount ?? 0, 2) }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Paid Amount : </div>
                        <div class="col-sm-3">{{ numberFormat($transaction->paid_amount ?? 0, 2) }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Transaction Number : </div>
                        <div class="col-sm-3">{{ $transaction->transaction_id ?? '' }}</div>
                        <div class="col-sm-3 text-end fw-semibold"></div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-3 text-end fw-semibold">Current Status : </div>
                        <div class="col-sm-3">{{ $transaction->status->name }}</div>
                    </div>
                    @if ($status == TransactionStatus::FAIL->value)    
                        <div class="row mb-2">
                            <label class="col-sm-3 col-form-label text-end">Remarks&nbsp;:<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <textarea name="notes" id="notes" class="form-control"></textarea>
                                <span class="text-danger validate-err-msg" id="notes-error"></span>
                            </div>
                        </div>
                    @else
                        <div class="row mb-2">
                            <label for="payment_type" class="col-sm-4 col-form-label text-end">Transaction Status&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                            <div class="col-sm-7">
                                <select class="form-select" name="transaction_status" id="transaction_status">
                                    <option value="">Select</option>
                                    @foreach ($transaction_status as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="amount" class="col-sm-4 col-form-label text-end">Amount&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control text-end" id="amount" name="amount" placeholder="Enter the amount to be paid." value="{{ $bill->balance_amount ?? 0 }}">
                                    <span class="input-group-text"><i class="bi-currency-rupee"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="payment_mode" class="col-sm-4 col-form-label text-end">Payment Mode&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="payment_mode" name="payment_mode" placeholder="Enter the payment mode.">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="bank_ref" class="col-sm-4 col-form-label text-end">Bank Ref. No&nbsp;:&nbsp;<i class="text-danger">*&nbsp;</i></label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="bank_ref" name="bank_ref" placeholder="Enter the Bank Ref. number.">
                            </div>
                        </div>
                    @endif
                    <div class="m-1" id="update-transaction-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-8">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-upload" aria-hidden="true">&nbsp;</i>
Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>

@include('scripts.ajax-form-submit', ['form' => 'update-transaction'])