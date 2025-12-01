<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Execute Consumer</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="executed-success">
                <form id="executed-form" action="{{ url('consumers/execution/'.$id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="col-form-label">Isometric Image<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                            <input type="file" name="dc_file_list[]" id="dc_file_list_0" class="form-control form-control-sm"/>
                            <span class="text-danger" id="dc_file_list-error"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">Installation Image<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                            <input type="file" name="dc_file_list[]" id="dc_file_list_1" class="form-control form-control-sm"/>
                        </div>
                    </div>
                    <div class="row mb-2">  
                        <div class="col-md-6">
                            <label class="col-form-label">Meter Number<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                            <input type="text" name="meter_no" id="meter_no" class="form-control form-control-sm"/>
                            <span class="text-danger" id="meter_no-error"></span>
                        </div>
                        <div class="col-md-6">
                        <label class="col-form-label">Meter Reading<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                            <input type="text" name="meter_reading" id="meter_reading" class="form-control form-control-sm"/>
                            <span class="text-danger" id="meter_reading-error"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label">Installation Note<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                        <div class="col-md-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="mb-3" id="executed-error"></div>
                    <div class="row mb-3">
                        <div class="col-md-12 col-sm-12">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Execute
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
@include('scripts.ajax-file-submit', ['form' => 'executed'])
