{{-- HSC Form --}}
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
                            <span class="text-danger validate-err-msg" id="dc_file-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label">Note&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger validate-err-msg" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="mb-3" id="hsc-error"></div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success text-bg-purple">
                            <i class="bi bi-link-45deg" aria-hidden="true">&nbsp;</i>HSC
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
@include('scripts.ajax-file-submit', ['form' => 'hsc'])
