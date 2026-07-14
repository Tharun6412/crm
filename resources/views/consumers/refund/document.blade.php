{{-- HSC Form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Refund Documents</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="document-success">
                <form id="document-form" action="{{ url('consumers/refunds/updateDocument/'.$consumer_refund->id) }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-md-2 col-form-label">Document&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-md-10">
                            <input type="file" name="dc_file" id="dc_file" class="form-control form-control-sm"/>
                            <span class="text-danger validate-err-msg" id="dc_file-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-md-2 col-form-label">Note&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-md-10">
                            <textarea name="notes" id="notes" class="form-control" placeholder="Enter notes.."></textarea>
                            <span class="text-danger validate-err-msg" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="mb-3" id="document-error"></div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-cloud-arrow-up-fill" aria-hidden="true">&nbsp;</i>Upload
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
@include('scripts.ajax-file-submit', ['form' => 'document'])
