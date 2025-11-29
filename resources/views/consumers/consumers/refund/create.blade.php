<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Consumer Refund Initiate</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Consumer Number</td>
                                    <td>:</td>
                                    <td>{{ $consumer_scheme->consumer->crn }}</td>
                                </tr>
                                <tr>
                                    <td>Consumer Type</td>
                                    <td>:</td>
                                    <td>{{ $consumer_scheme->consumer->segment->name }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td>{{ $consumer_scheme->consumer->status->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>{{ $consumer_scheme->consumer->titleDisplay->name }}&nbsp;{{ $consumer_scheme->consumer->name }}</td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td>:</td>
                                    <td>{{ $consumer_scheme->consumer->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Refund Status</td>
                                    <td>:</td>
                                    <td>{{ "Not refunded" }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="clearfix">
                        <div class="float-start">
                            <span class="h5 text-decoration-underline">Security Deposit Details</span>
                        </div>
                        <div class="float-end">
                            <span><strong>Scheme</strong>&nbsp;:&nbsp;{{ $consumer_scheme->scheme->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <table class="table table-bordered"> 
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Registration Charges</th>
                                <th>Security Deposit</th>
                                <th>Consumption Deposit</th>
                                <th>Total Deposit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Charges</td>
                                <td class="text-end">{{ numberFormat($consumer_scheme->scheme->registration) }}</td>
                                <td class="text-end">{{ numberFormat($consumer_scheme->security_deposit) }}</td>
                                <td class="text-end">{{ numberFormat($consumer_scheme->consumption_deposit) }}</td>
                                <td class="text-end">{{ numberFormat($consumer_scheme->total_deposit) }}</td>
                            </tr>
                            <tr>
                                <td>Payments</td>
                                <td class="text-end">{{ numberFormat($consumer_scheme->scheme->registration) }}</td>
                                <td class="text-end" colspan="2">{{ numberFormat($consumer_scheme->security_deposit + $consumer_scheme->consumption_deposit) }}</td>
                                <td class="text-end">{{ numberFormat($consumer_scheme->total_deposit) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr/>
                <div class="row">
                    <div class="clearfix">
                        <div class="float-left">
                            <strong class="text-decoration-underline">Outstanding Balance Sheet</strong>
                        </div>
                    </div>
                </div>
                <div class="mt-2" id="refund-success">
                    <form id="refund-form" action="{{ url('consumers/refund/'.$id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <table class="table table-bordered"> 
                            <thead>
                                <th>#</th>
                                <th>Description</th>
                                <th>Credit</th>
                                <th>Debit</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Refundable Security Deposit</td>
                                    <td class="text-end">{{ numberFormat($consumer_scheme->total_deposit) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Invoice</td>
                                    <td></td>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Geyser Deposit</td>
                                    <td></td>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Services</td>
                                    <td></td>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Custom Invoice</td>
                                    <td></td>
                                    <td class="text-end">0</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Disconnection Charges</td>
                                    <td></td>
                                    <td>
                                        <div class="row">
                                            <div class="input-group">
                                                <span class="input-group-text">&#8377;</span>
                                                <input type="text" name="disconnect_amt" id="disconnect_amt" class="form-control" value="0.00" onchange="disconnectionAmt(this.value)"/>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end">Total</td>
                                    <td class="text-end" id="credit_amt">{{ $consumer_scheme->total_deposit }}</td>
                                    <td class="text-end" id="debit_amt">100</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end">Final Refundable Amount</td>
                                    <td class="text-end" colspan="2" id="refundable_amt">{{ $consumer_scheme->total_deposit }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end">Consumer Payable Amount</td>
                                    <td class="text-end" colspan="2" id="payable_amt">{{ 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-danger" id="refund-error"></div>
                        <div class="row mb-3">
                            <div class="col-md-12 col-sm-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-check" aria-hidden="true">&nbsp;</i>Initiate Redfund
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'refund'])
<script>
    // Refund Amount Update Details
    function disconnectionAmt(disconnect_amt) {
        disconnect_amt = parseFloat(disconnect_amt) || 0;
        // Get credit amount
        let credit_amt = parseFloat($('#credit_amt').html()) || 0;
        let debit_amt = parseFloat($('#debit_amt').html()) || 0;
        total_amt = parseFloat(debit_amt) + parseFloat(disconnect_amt);
        // Update debit amount in table
        $('#debit_amt').html(total_amt.toFixed(2));

        // Calculate difference
        let diff_amt = credit_amt - total_amt;
        if (diff_amt >= 0) {
            // Refundable amount
            $('#refundable_amt').html(diff_amt.toFixed(2));
            $('#payable_amt').html("0.00");
        } else {
            // Payable amount (positive number)
            $('#payable_amt').html(Math.abs(diff_amt).toFixed(2));
            $('#refundable_amt').html("0.00");
        }
    }

</script>
