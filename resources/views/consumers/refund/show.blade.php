<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Refund Details</h4>
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
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
