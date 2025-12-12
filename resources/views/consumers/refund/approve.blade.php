<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Approve Refund</h4>
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
                                    <td>{{ $refund_data->consumer->titleDisplay->name }}&nbsp;{{ $refund_data->consumer->name }}</td>
                                </tr>
                                <tr>
                                    <td>CRN</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->scheme->consumer->crn }}</td>
                                </tr>
                                <tr>
                                    <td>Consumer Type</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->scheme->consumer->segment->name }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td>{{ $refund_data->consumer->scheme->consumer->status->name }}</td>
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
            <div class="border p-3 rounded mt-3"> 
                <div class="row">
                    <h4 class="fw-semibold text-decoration-underline">Refund Details</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-8">Security Paid Deposit</dt>
                            <dd class="col-sm-4">{{ numberFormat($refund_data->consumer->scheme->paid_deposit) }}</dd>
                            <dt class="col-sm-8">Total Pending Balance</dt>
                            <dd class="col-sm-4">{{ numberFormat($refund_data->outstanding_amount) }}</dd>
                            <dt class="col-sm-8"><span class="text-nowrap">Disconnection Charges</span></dt>
                            <dd class="col-sm-4">{{ numberFormat($refund_data->disconnection_amount) }}</dd>
                            <dt class="col-sm-8"><span class="text-nowrap">Total Refundable Amount</span></dt>
                            <dd class="col-sm-4">{{ numberFormat($refund_data->refund_amount) }}</dd>
                        </dl>
                    </div>
                </div> 
            </div>               
            <div class="mt-3" id="approve-success">
                <form id="approve-form" action="{{ url('consumers/refunds/approveUpdate/'.$refund_data->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <label class="col-form-label col-sm-2 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <textarea name="notes" id="notes" class="form-control" placeholder="Enter here"></textarea>
                        </div>
                    </div>
                    <div class="text-danger mt-3" id="approve-error"></div>
                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Approve
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
@include('scripts.ajax-form-submit', ['form' => 'approve'])
