<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Reconnection Request</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="fw-semibold text-decoration-underline">Consumer Details</h4>
                    <dl class="row">
                        <dt class="col-sm-3">Name</dt>            
                        <dd class="col-sm-9">{{ $consumer->name }}</dd>
                        <dt class="col-sm-3">Type</dt>            
                        <dd class="col-sm-9">{{ $consumer->segment->name }}</dd>
                        <dt class="col-sm-3">Geo Area</dt>            
                        <dd class="col-sm-9">{{ $consumer->ga->name }}</dd>
                        <dt class="col-sm-3">District</dt>            
                        <dd class="col-sm-9">{{ $consumer->district->name }}</dd>            
                    </dl>
                </div>
                <div class="col-md-6">
                    <h4 class="fw-semibold text-decoration-underline">Consumer Scheme Details</h4>
                    <dl class="row">
                        <dt class="col-sm-4">Scheme</dt>
                        <dd class="col-sm-8">{{ $consumer->scheme->scheme->name }}</dd>
                        <dt class="col-sm-4 text-nowrap">Total Deposit</dt>
                        <dd class="col-sm-8">{{ numberFormat($consumer->scheme->total_deposit) }}</dd>
                        <dt class="col-sm-4 text-nowrap">Paid Deposit</dt>
                        <dd class="col-sm-8">{{ numberFormat($consumer->scheme->paid_deposit) }}</dd>
                        <dt class="col-sm-4 text-nowrap">Balance Deposit</dt>
                        <dd class="col-sm-8">{{ numberFormat($consumer->scheme->balance) }}</dd>
                    </dl>
                </div>
            </div>
            <div id="reconnect-success" class="p-2">
                <h4 class="fw-semibold text-decoration-underline">Reconnection Request</h4>
                 <form id="reconnect-form" action="{{ url('consumers/reconnect/'.$consumer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-4 text-end">Reconnection Charges<span class="text-danger">&nbsp;*</span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <select class="form-select form-select-sm" name="item_id" id="item_id">
                                <option value="">select</option>
                                @foreach ($inv_items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}&nbsp;-&nbsp;{{ $item->price }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label col-sm-4 text-end">Notes<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="mb-3" id="reconnect-error"></div>
                    <div class="row mb-3">
                        <div class="col-md-12 col-sm-12">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Reconnect
                            </button>
                        </div>
                    </div>
                 </form>
            </div> 
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'reconnect'])
