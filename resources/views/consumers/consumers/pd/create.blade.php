<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Temporary Disconnection Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="pd-success">
                <form id="pd-form" action="{{ url('consumers/permanentDisconnect/'.$id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label class="col-form-label">Notes</label>
                        <div class="col-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label mb-2">Permanent Disconnection Status</label>
                        <div class="col-12 d-flex gap-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="status" id="status1" value="1">
                                <label for="status1" class="form-check-label">Enable</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="pd-error"></div>
                    <div class="row mb-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="mdi mdi-check" aria-hidden="true">&nbsp;</i>Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'pd'])
