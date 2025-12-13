<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Refund Details - #{{ $refund_data->request_no }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer details with refud details --}}
            <x-consumer.refund-details :refund="$refund_data" type="2" class="bg-info-subtle" />
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
            <div class="my-2">
                <h4 class="fw-semibold text-decoration-underline">Consumer Refund Status</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
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
