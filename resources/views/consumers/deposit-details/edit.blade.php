<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pay Security Deposit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card mb-2">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="130">Consumer type</td>
                                    <td width="1%">:</td>
                                    <td>
                                        <label class="text-success">{{ $consumer->segment->name }}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Consumer code</td>
                                    <td>:</td>
                                    <td><label class="text-success">{{ $consumer->crn }}</label></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td><span>{{ $consumer->status->name }}</span></td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>{{ $consumer->titleDisplay->name }}&nbsp;{{ $consumer->fname }}&nbsp;{{ $consumer->lname }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="170">Scheme Name</td>
                                    <td width="1%">:</td>
                                    <td class="text-end">{{ $consumer->scheme->scheme->name }}</td>
                                </tr>
                                <tr>
                                    <td>Total Deposit</td>
                                    <td>:</td>
                                    <td class="text-end">{{ numberFormat($consumer->scheme->total_deposit) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Paid Deposit</td>
                                    <td>:</td>
                                    <td class="text-end">{{ numberFormat($consumer->scheme->paid_deposit) }}</td>
                                </tr>
                                <tr>
                                    <td>Balance Deposit</td>
                                    <td>:</td>
                                    <td class="text-end">{{ numberFormat($consumer->scheme->balance) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="sdpayment-success">
                @if ($consumer->scheme->status != 1)
                    <form id="sdpayment-form" action="{{ url('consumers/payDeposit/'.$consumer->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-2">
                            <label class="col-form-label col-sm-4 text-end">Minimum Amount Payable&nbsp;:</label>
                            <div class="col-sm-6">
                                <label class="col-form-label">
                                    <strong>{{ numberFormat($consumer->scheme->scheme->min_payment) }}</strong>
                                </label>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-form-label col-sm-4 text-end">Total Amount Payable<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" name="amount" id="amount" placeholder="Total payable amount." value="{{ $consumer->scheme->scheme->min_payment }}">
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
                <h4 class="fw-semibold text-decoration-underline">Security Deposit Paid History</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Payment Type</th>
                            <th>Transaction Number</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">Balance</th>
                            <th>Payment Status</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($consumer->sdPayment->count() > 0)
                            @foreach ($consumer->sdPayment as $sd)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sd->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $sd->paymentType->name }}</td>
                                    <td>{{ $sd->transaction_number }}</td>
                                    <td class="text-end">{{ numberFormat($sd->amount) }}</td>
                                    <td class="text-end">{{ numberFormat($sd->balance) }}</td>
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
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'sdpayment'])
