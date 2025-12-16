<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Refund Details - #{{ $refund_data->request_no }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer details with refud details --}}
            <x-consumer.refund-details :refund="$refund_data" type="2" class="bg-info-subtle" />
            <div class="border p-2 square mt-2">
                <h4 class="fw-semibold text-decoration-underline">Refund Details</h4>
                @if ($refund_data->status_id == 1)
                    <div class="alert alert-warning">Refund Request is in process</div>
                @else
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
                @endif
            </div>
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
