<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">HSC</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="hsc-success">
                <form id="hsc-form" action="{{ url('consumers/hsconnect/'.$id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label class="col-form-label">HSC Image&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-12">
                            <input type="file" name="dc_file" id="dc_file" class="form-control form-control-sm"/>
                            <span class="text-danger" id="dc_file-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label">Note&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="mb-3" id="hsc-error"></div>
                    <div class="row mb-2">
                        <div class="col-md-12 col-sm-12">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>HSC
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
@include('scripts.ajax-file-submit', ['form' => 'hsc'])
