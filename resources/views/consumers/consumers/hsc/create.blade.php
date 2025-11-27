<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Consumer Status Note</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="hsc-success">
                <form id="hsc-form" action="{{ url('consumers/hscAction/'.$id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label class="col-form-label">HSC Image<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                        <div class="col-12">
                            <input type="file" name="dc_file" id="dc_file" class="form-control"/>
                            <span class="text-danger" id="dc_file-error"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label">Note<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                        <div class="col-md-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="mb-3" id="hsc-error"></div>
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
@include('scripts.ajax-file-submit', ['form' => 'hsc'])
