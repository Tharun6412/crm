<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pay Security Deposit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @php
                // print "<pre>"; print_r($consumer_scheme->id);exit;
            @endphp
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
                                <td><label class="text-success">{{ $consumer->t_crn }}</label></td>
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
                            <tr>
                                <td>Mobile</td>
                                <td>:</td>
                                <td>{{ $consumer->phone }}</td>
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
                                <td class="text-end">{{ $consumer_scheme->scheme->name }}</td>
                            </tr>
                            <tr>
                                <td>Security Deposit</td>
                                <td>:</td>
                                <td class="text-end">{{ ($consumer_scheme->security_deposit) }}</span></td>
                            </tr>
                            <tr>
                                <td>Consumption deposit</td>
                                <td>:</td>
                                <td class="text-end">{{ ($consumer_scheme->consumption_deposit) }}</td>
                            </tr>
                            <tr>
                                <td>Registration Charges</td>
                                <td>:</td>
                                <td class="text-end">{{ ($consumer_scheme->scheme->registration) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr />
            @if ($consumer_scheme->status != 1)
                @if ($consumer_scheme->scheme->min_payment > 0)
                    <div id="security-deposit-pay" class="mt-4">
                        <form id="pay-deposit-form" name="pay-deposit-form">
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-4 text-end">Minimum Amount Payable<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                                <div class="col-sm-6">
                                    <label class="col-form-label">
                                        <strong>{{ ($consumer_scheme->scheme->min_payment) }}</strong>
                                    </label>
                                    <div><small>(&nbsp;Registration charges + Consumption deposit&nbsp;)</small></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-4 text-end">Total Amount Payable<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-sm" name="amount" id="amount" placeholder="Total payable amount.">
                                </div>
                            </div>
                            <div class="row mb-3">
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
                            <div class="row mb-3">
                                <label class="col-sm-4 text-end col-form-label">Transaction No.<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-sm" name="transaction_no" id="transaction_no" placeholder="Transaction No.">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label text-end">Note&nbsp;:</label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" rows="4" name="notes" id="notes" ></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-10 col-sm-10">
                                    <div class="text-end">
                                        <button type="button" class="btn btn-success btn-sm" onclick="paySecurityDepositExt(this)">
                                            <i class="mdi mdi-check" aria-hidden="true">&nbsp;</i>Pay Deposit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-warning">Payable amount should be grater than 0.</div>                    
                @endif
            @else
                <div class="alert alert-warning">This consumer is already Paid.</div>
            @endif  
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close">&nbsp;</i>Close</button>
        </div>
    </div>
</div>