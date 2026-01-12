<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Activate Consumer</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="activated-success">
                <form id="activated-form" action="{{ url('consumers/activate/'.$id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label class="col-form-label">Activate Image</label>
                        <div class="col-12">
                            <input type="file" name="dc_file" id="dc_file" class="form-control form-control-sm"/>
                            <span class="text-danger validate-err-msg" id="dc_file-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger validate-err-msg" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="mb-3" id="activated-error"></div>
                    <div class="row mb-2">
                        <div class="col-md-12 col-sm-12">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Activate
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
@include('scripts.ajax-file-submit', ['form' => 'activated'])
