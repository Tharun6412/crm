<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Accept or Reject Consumer</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="registered-success">
                <form id="registered-form" action="{{ url('consumers/accept/'.$id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label class="col-form-label">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label">Acceptance Status&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-12 d-flex gap-4">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="status" id="status1" value="1">
                                <label for="status1" class="form-check-label text-success"><i class="bi bi-check-all"></i>&nbsp;Accept</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="status" id="status2" value="2">
                                <label for="status2" class="form-check-label text-danger"><i class="bi bi-ban"></i>&nbsp;Reject</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="registered-error"></div>
                    <div class="row mb-3">
                        <div class="col-md-12 col-sm-12">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Update Status
                            </button>
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
@include('scripts.ajax-form-submit', ['form' => 'registered'])
