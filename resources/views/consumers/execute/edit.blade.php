{{-- Execution form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Execute Consumer</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="executed-success">
                <form id="executed-form" action="{{ url('consumers/execute/' . $id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="col-form-label">Isometric Image&nbsp;:<span class="text-danger">*</span></label>
                            <input type="file" name="dc_file_list[]" id="dc_file_list_0" class="form-control"/>
                            <span class="text-danger validate-err-msg" id="dc_file_list_0-error"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">Installation Image&nbsp;:<span class="text-danger">*</span></label>
                            <input type="file" name="dc_file_list[]" id="dc_file_list_1" class="form-control"/>
                            <span class="text-danger validate-err-msg" id="dc_file_list_1-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">  
                        <div class="col-md-4">
                            <label class="col-form-label">Meter Number&nbsp;:<span class="text-danger">*</span></label>
                            <input type="text" name="meter_no" id="meter_no" placeholder="Enter Meter Number" class="form-control"/>
                            <span class="text-danger validate-err-msg" id="meter_no-error"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label">Meter Serial Number&nbsp;:</label>
                            <input type="text" name="meter_serial_no" id="meter_serial_no" placeholder="Enter Meter Serial Number" class="form-control"/>
                            <span class="text-danger validate-err-msg" id="meter_serial_no-error"></span>
                        </div>
                        <div class="col-md-4">
                        <label class="col-form-label">Meter Reading&nbsp;:<span class="text-danger">*</span></label>
                            <input type="text" name="meter_reading" id="meter_reading" class="form-control" placeholder="Enter Meter Reading" />
                            <span class="text-danger validate-err-msg" id="meter_reading-error"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label">Installation Note&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger validate-err-msg" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="m-1" id="executed-error"></div>
                    <div class="text-start">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-person-gear" aria-hidden="true">&nbsp;</i>Execute
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
@include('scripts.ajax-file-submit', ['form' => 'executed'])
