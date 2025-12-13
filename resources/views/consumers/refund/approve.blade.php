<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Approve Refund</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer details with refud details --}}
            <x-consumer.refund-details :refund="$refund_data" type="2" class="bg-info-subtle" />
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
