<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Consumer Process</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card mb-2">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->titleDisplay->name }}&nbsp;{{ $refund_data->consumer->name }}</td>
                                </tr>
                                <tr>
                                    <td>CRN</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->crn }}</td>
                                </tr>
                                <tr>
                                    <td>Consumer Type</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->segment->name }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->status->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Scheme</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->scheme->scheme->name }}</td>
                                </tr>
                                <tr>
                                    <td>Paid Deposit</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->scheme->paid_deposit }}</td>
                                </tr>
                                <tr>
                                    <td>Balance Deposit</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->scheme->balance }}</td>
                                </tr>
                                <tr>
                                    <td>Refund Status</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->status->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="clearfix">
                    <div class="float-start">
                        <span class="h5 text-decoration-underline">Security Deposit Details</span>
                    </div>
                    <div class="float-end">
                        <span><strong>Scheme</strong>&nbsp;:&nbsp;{{ $refund_data->consumer->scheme->scheme->name }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <table class="table table-bordered"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Security Deposit</th>
                            <th>Consumption Deposit</th>
                            <th>Total Deposit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Charges</td>
                            <td class="text-end">{{ numberFormat($refund_data->consumer->scheme->security_deposit) }}</td>
                            <td class="text-end">{{ numberFormat($refund_data->consumer->scheme->consumption_deposit) }}</td>
                            <td class="text-end">{{ numberFormat($refund_data->consumer->scheme->total_deposit) }}</td>
                        </tr>
                        <tr>
                            <td>Payments</td>
                            <td class="text-end" colspan="2">{{ numberFormat($refund_data->consumer->scheme->paid_deposit) }}</td>
                            <td class="text-end">{{ numberFormat($refund_data->consumer->scheme->paid_deposit) }}</td>
                        </tr>
                        <tr>
                            <td class="text-end" colspan="3">Balance Deposit</td>
                            <td class="text-end">{{ numberFormat($refund_data->consumer->scheme->balance) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="clearfix">
                    <div class="float-left">
                        <strong class="text-decoration-underline">Outstanding Balance Sheet</strong>
                    </div>
                </div>
            </div>
            <div class="mt-2" id="process-success">
                @php
                    $inv_amt = [];
                    $inv_tot_sum = 0;
                    foreach ($inv_types as $key => $type) {
                        $inv_amt[$type->id] = $invoice_data[$type->id] ?? 0;
                        $inv_tot_sum += $inv_amt[$type->id];
                    }
                    $tot_refund_amt = $refund_data->consumer->scheme->paid_deposit - $inv_tot_sum;
                @endphp
                <form id="process-form" action="{{ url('consumers/refunds/processUpdate/'.$refund_data->id) }}" method="POST">
                    @csrf
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
                                <td class="text-end">{{ numberFormat($refund_data->consumer->scheme->paid_deposit) }}</td>
                                <td class="text-end"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Invoice</td>
                                <td></td>
                                <td class="text-end">{{ $inv_amt[2] }}</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Geyser Deposit</td>
                                <td></td>
                                <td class="text-end">{{ $inv_amt[3] }}</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Services</td>
                                <td></td>
                                <td class="text-end">{{ $inv_amt[1] }}</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Custom Invoice</td>
                                <td></td>
                                <td class="text-end">{{ $inv_amt[4] }}</td>
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
                                            <input type="hidden" name="inv_tot_sum" id="inv_tot_sum" class="form-control" value="{{ $inv_tot_sum }}"/>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end">Total</td>
                                <td class="text-end" id="credit_amt">{{ $refund_data->consumer->scheme->paid_deposit }}</td>
                                <td class="text-end" id="debit_amt">{{ $inv_tot_sum }}</td>
                            </tr>
                            <tr>

                                <td colspan="2" class="text-end">Final Refundable Amount</td>
                                <td class="text-end" colspan="2" id="refundable_amt">{{ $tot_refund_amt > 0 ? $tot_refund_amt : 0 }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end">Consumer Payable Amount</td>
                                <td class="text-end" colspan="2" id="payable_amt">{{ $tot_refund_amt < 0 ? abs($tot_refund_amt) : 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-danger" id="process-error"></div>
                    <div class="row mb-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-success" id="initiate_refund">
                                    <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Initiate Redfund
                                </button>
                            </div>
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
@include('scripts.ajax-form-submit', ['form' => 'process'])
<script>
    // Refund Amount Update Details
    function disconnectionAmt(disconnect_amt) {
        disconnect_amt = parseFloat(disconnect_amt) || 0;
        // Get credit amount
        let credit_amt = parseFloat($('#credit_amt').html()) || 0;
        let inv_tot_amt = parseFloat($('#inv_tot_sum').val()) || 0;
        total_amt = parseFloat(inv_tot_amt) + parseFloat(disconnect_amt);
        // Update debit amount in table
        $('#debit_amt').html(total_amt.toFixed(2));

        // Calculate difference
        let diff_amt = credit_amt - total_amt;
        if (diff_amt > 0) {
            // Refundable amount
            $('#refundable_amt').html(diff_amt.toFixed(2));
            $('#payable_amt').html("0.00");
            $('#initiate_refund').removeClass('d-none');
        } else {
            // Payable amount (positive number)
            $('#payable_amt').html(Math.abs(diff_amt).toFixed(2));
            $('#refundable_amt').html("0.00");
            $('#initiate_refund').addClass('d-none');
        }
    }
</script>
