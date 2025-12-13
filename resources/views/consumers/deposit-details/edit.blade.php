<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pay Security Deposit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer basic details component --}}
            <x-consumer.basic-details :consumer="$consumer" type="2" class="bg-info-subtle" />
            <div id="sdpayment-success" class="border rounded p-2">
                @if ($consumer->scheme->status != 1)
                    <form id="sdpayment-form" action="{{ url('consumers/payDeposit/'.$consumer->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-2">
                            <label class="col-form-label col-sm-4 text-end">Outstanding balance&nbsp;:</label>
                            <div class="col-sm-6">
                                <label class="col-form-label">
                                    <strong>{{ numberFormat($consumer->scheme->balance, 2) }}</strong>
                                </label>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-form-label col-sm-4 text-end">Amount<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm text-end" name="amount" id="amount" placeholder="Total payable amount." value="{{ $consumer->scheme->balance }}">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-form-label col-sm-4 text-end">Payment Mode<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                            <div class="col-sm-6">
                                <select class="form-select form-select-sm" name="payment_type" id="payment_type">
                                    <option value="">select</option>
                                    @foreach ($payment_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-4 text-end col-form-label">Transaction No.<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" name="transaction_no" id="transaction_no" placeholder="Transaction No.">
                            </div>
                        </div>
                        <div class="mb-2" id="sdpayment-error"></div>
                        <div class="row mb-2">
                            <div class="col-md-10 col-sm-10">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Pay Deposit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">This consumer is already Paid.</div>
                @endif 
            </div>
            <div class="mt-3">
                <h4 class="fw-semibold text-decoration-underline">Security Deposit Payments</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-primary fs-sm">
                        <thead class="table-primary">
                            <tr>
                                <th width="1%" nowrap>S.No</th>
                                <th>Date</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Balance</th>
                                <th>#Transaction</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($consumer->sdPayment->count() > 0)
                                @foreach ($consumer->sdPayment as $sd)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td nowrap>{{ $sd->created_at->format('d-m-Y') }}</td>
                                        <td class="text-end">{{ numberFormat($sd->amount) }}</td>
                                        <td class="text-end">{{ numberFormat($sd->balance) }}</td>
                                        <td>{{ $sd->transaction_number }}</td>
                                        <td>{{ $sd->paymentType->name }}</td>
                                        <td>{{ $sd->status->name }}</td>
                                        <td>{{ $sd->createdBy->first_name }}&nbsp;{{ $sd->createdBy->last_name }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">No records found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'sdpayment'])
