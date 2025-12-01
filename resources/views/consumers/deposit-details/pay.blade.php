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
                                        <label class="text-success">{{ $consumer_scheme->consumer->segment->name }}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Consumer code</td>
                                    <td>:</td>
                                    <td><label class="text-success">{{ $consumer_scheme->consumer->t_crn }}</label></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td><span>{{ $consumer_scheme->consumer->status->name }}</span></td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>{{ $consumer_scheme->consumer->titleDisplay->name }}&nbsp;{{ $consumer_scheme->consumer->fname }}&nbsp;{{ $consumer_scheme->consumer->lname }}</td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td>:</td>
                                    <td>{{ $consumer_scheme->consumer->phone }}</td>
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
                                    <td class="text-end">{{ numberFormat($consumer_scheme->security_deposit) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Consumption deposit</td>
                                    <td>:</td>
                                    <td class="text-end">{{ numberFormat($consumer_scheme->consumption_deposit) }}</td>
                                </tr>
                                <tr>
                                    <td>Registration Charges</td>
                                    <td>:</td>
                                    <td class="text-end">{{ numberFormat($consumer_scheme->scheme->registration) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="trpayment-success">
                @if ($consumer_scheme->status != 1)
                    @if ($consumer_scheme->scheme->min_payment > 0)
                        <form id="trpayment-form" action="{{ url('consumers/trPayment/'.$consumer_scheme->consumer_id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row mb-2">
                                <label class="col-form-label col-sm-4 text-end">Minimum Amount Payable<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                                <div class="col-sm-6">
                                    <label class="col-form-label">
                                        <strong>{{ numberFormat($consumer_scheme->scheme->min_payment) }}</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-form-label col-sm-4 text-end">Total Amount Payable<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-sm" name="amount" id="amount" placeholder="Total payable amount." value="{{ $consumer_scheme->scheme->min_payment }}">
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
                            <div class="row mb-2">
                                <label class="col-sm-4 col-form-label text-end">Note&nbsp;:</label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" rows="4" name="notes" id="notes" ></textarea>
                                </div>
                            </div>
                            <div class="mb-2" id="trpayment-error"></div>
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
                        <div class="alert alert-warning">Payable amount should be grater than 0.</div>                    
                    @endif
                @else
                    <div class="alert alert-warning">This consumer is already Paid.</div>
                @endif 
            </div> 
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'trpayment'])
