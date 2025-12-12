<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Consumer Refund Request</h4>
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
                                    <td>{{ $consumer_scheme->consumer->titleDisplay->name }}&nbsp;{{ $consumer_scheme->consumer->name }}</td>
                                </tr>
                                <tr>
                                    <td>CRN</td>
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
                                    <td>Added Date</td>
                                    <td>:</td>
                                    <td>{{ $consumer_scheme->consumer->created_at->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Added By</td>
                                    <td>:</td>
                                    <td>{{ $consumer_scheme->consumer->createdBy->first_name }}&nbsp;{{ $consumer_scheme->consumer->createdBy->last_name }}</td>
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
                        <span><strong>Scheme</strong>&nbsp;:&nbsp;{{ $consumer_scheme->scheme->name }}</span>
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
                            <td class="text-end">{{ numberFormat($consumer_scheme->security_deposit) }}</td>
                            <td class="text-end">{{ numberFormat($consumer_scheme->consumption_deposit) }}</td>
                            <td class="text-end">{{ numberFormat($consumer_scheme->total_deposit) }}</td>
                        </tr>
                        <tr>
                            <td>Payments</td>
                            <td class="text-end" colspan="2">{{ numberFormat($consumer_scheme->paid_deposit) }}</td>
                            <td class="text-end">{{ numberFormat($consumer_scheme->paid_deposit) }}</td>
                        </tr>
                        <tr>
                            <td class="text-end" colspan="3">Balance Deposit</td>
                            <td class="text-end">{{ numberFormat($consumer_scheme->balance) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="clearfix">
                    <div class="float-left">
                        <strong class="text-decoration-underline">Refund request</strong>
                    </div>
                </div>
            </div>                
            @if ($refund_data)
                <div class="mt-3">
                    <div class="alert alert-warning">Request Number :&nbsp;{{ $refund_data->request_no }}</div>
                    <div class="alert alert-warning">Refund Status :&nbsp;{{ $refund_data->status->name }}</div>
                </div>
            @else    
                <div class="mt-2" id="refund-success">
                    <form id="refund-form" action="{{ url('consumers/refunds/refundRequestUpdate/'.$id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <label class="col-form-label col-sm-2">Notes<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea name="notes" id="notes" class="form-control" placeholder="Enter here"></textarea>
                            </div>
                        </div>
                        <div class="text-danger mt-3" id="refund-error"></div>
                        <div class="row mt-3">
                            <div class="col-md-12 col-sm-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Initiate Redfund
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'refund'])
