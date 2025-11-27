<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Consumer Status Note</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="executed-success">
                <form id="executed-form" action="{{ url('consumers/execution/'.$id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label class="col-form-label">Isometric Image<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                        <div class="col-12">
                            <input type="file" name="dc_file_list[]" id="dc_file_list_0" class="form-control"/>
                            <span class="text-danger" id="dc_file_list-error"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label">Installation Image<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                        <div class="col-12">
                            <input type="file" name="dc_file_list[]" id="dc_file_list_1" class="form-control"/>
                        </div>
                    </div>
                    <div class="row mb-3">  
                        <label class="col-form-label">Meter Number<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                        <div class="col-md-12">
                            <input type="text" name="meter_no" id="meter_no" class="form-control"/>
                            <span class="text-danger" id="meter_no-error"></span>
                        </div>
                    </div>
                    <div class="row mb-3">  
                        <label class="col-form-label">Meter Reading<span class="text-danger">&nbsp;*:&nbsp;</span></label>
                        <div class="col-md-12">
                            <input type="text" name="meter_reading" id="meter_reading" class="form-control"/>
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
@include('scripts.ajax-file-submit', ['form' => 'executed'])
