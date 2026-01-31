<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Permanent Disconnect</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="pd-success">
                <form id="pd-form" action="{{ url('consumers/pdisconnect/'.$id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label class="col-form-label">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="mb-3" id="pd-error"></div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle" aria-hidden="true">&nbsp;</i>Disconnect Permanently
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'pd'])
