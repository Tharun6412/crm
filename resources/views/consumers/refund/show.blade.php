<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">View Details</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h4 class="fw-semibold text-decoration-underline">Consumer Details</h4>
            <div class="card mb-2">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->titleDisplay->name }}&nbsp;{{ $refund_data->consumer->fname }}</td>
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
                                    <td>Request Number</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->request_no }}</td>
                                </tr>
                                <tr>
                                    <td>Requested Date</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->created_at->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Requested By</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->createdBy->first_name }}&nbsp;{{ $refund_data->createdBy->last_name }}</td>
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
            @if ($refund_data->status_id > 1)    
                <div class="border p-2 square mt-2">
                    <h4 class="fw-semibold text-decoration-underline">Refund Details</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-6">SD Paid Amount</dt>
                                <dd class="col-sm-6">{{ numberFormat($refund_data->sd_paid) }}</dd>
                                <dt class="col-sm-6">Outstanding Amount</dt>
                                <dd class="col-sm-6">{{ numberFormat($refund_data->outstanding_amount) }}</dd>
                                <dt class="col-sm-6 text-nowrap">Disconnection Charges</dt>
                                <dd class="col-sm-6">{{ numberFormat($refund_data->disconnection_amount) }}</dd>
                                <dt class="col-sm-6">Refundable Amount</dt>
                                <dd class="col-sm-6">{{ numberFormat($refund_data->refund_amount) }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                @if ($refund_data->status_id == 4)
                                        <dt class="col-sm-6">Payment Type</dt>
                                        <dd class="col-sm-6">{{ $refund_data->paymentType->name }}</dd>
                                        <dt class="col-sm-6">Transaction Number</dt>
                                        <dd class="col-sm-6">{{ $refund_data->transaction_id }}</dd>
                                        <dt class="col-sm-6">Transaction Date</dt>
                                        <dd class="col-sm-6">{{ $refund_data->transaction_date->format('d-m-Y') }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            @endif
            <div class="mt-2">
                <h4 class="fw-semibold text-decoration-underline">Consumer Refund History</h4>
            </div>
            <div class="mt-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($refund_data->refundStatus as $status)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $status->status->name }}</td>
                                <td>{{ $status->notes }}</td>
                                <td>{{ $status->createdBy->first_name }}&nbsp;{{ $status->createdBy->last_name }}</td>
                                <td>{{ $status->created_at->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
