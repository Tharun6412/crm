{{-- Activation Form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Activate Consumer</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="activated-success">
                <form id="activated-form" action="{{ url('consumers/activate/'.$consumer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label class="col-form-label">Activation Image</label>
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
                    <div class="mb-1 fs-5 fw-semibold text-primary">LPG Details&nbsp;:</div>
                    <div class="row mb-2 pt-2">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label for="lpg_consumer_number" class="form-label">LPG Consumer Number&nbsp;:<span class="text-danger">*</span></label>
                            <input type="text" name="lpg_consumer_number" id="lpg_consumer_number" class="form-control" placeholder="Enter LPG Consumer Number">
                            <span class="text-danger validate-err-msg" id="lpg_consumer_number-error"></span>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label for="lpg_id" class="form-label">LPG ID&nbsp;:<span class="text-danger">*</span></label>
                            <input type="text" name="lpg_id" id="lpg_id" class="form-control" placeholder="Enter LPG ID">
                            <span class="text-danger validate-err-msg" id="lpg_id-error"></span>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label for="lpg_omc_id" class="form-label">LPG OMC Type&nbsp;:<span class="text-danger">*</span></label>
                            <select name="lpg_omc_id" id="lpg_omc_id" class="form-select">
                                <option value="">Select OMC</option>
                                @foreach ($omcs as $omc )
                                    <option value="{{ $omc->id }}">{{ $omc->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="lpg_omc_id-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2 pt-2">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label for="registered_mobile" class="form-label">LPG Registered Mobile&nbsp;:<span class="text-danger">*</span></label>
                            <input type="text" name="registered_mobile" id="registered_mobile" class="form-control" placeholder="Enter Registered Mobile">
                            <span class="text-danger validate-err-msg" id="registered_mobile-error"></span>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label for="lpg_connections" class="form-label">LPG Connections&nbsp;:<span class="text-danger">*</span></label>
                            <input type="text" name="lpg_connections" id="lpg_connections" class="form-control" placeholder="Enter LPG Connections" value="{{ $consumer->lpg_connections ?? '' }}">
                            <span class="text-danger validate-err-msg" id="lpg_connections-error"></span>
                        </div>
                    </div>
                    {{-- Check meter details --}}
                    <div class="mb-1 fs-5 fw-semibold text-primary">Meter Details&nbsp;:</div>
                    @if ($consumer->activeMeter)
                        <div class="row mb-2">  
                            <div class="col-md-4">
                                <label class="col-form-label">Meter Number&nbsp;:<span class="text-danger">*</span></label>
                                <input type="text" name="meter_no" id="meter_no" placeholder="Enter Meter Number" class="form-control" value="{{ $consumer->activeMeter->meter_no ?? '' }}"/>
                                <span class="text-danger validate-err-msg" id="meter_no-error"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Meter Serial Number&nbsp;:</label>
                                <input type="text" name="meter_serial_no" id="meter_serial_no" placeholder="Enter Meter Serial Number" class="form-control" value="{{ $consumer->activeMeter->meter_serial_no ?? '' }}"/>
                                <span class="text-danger validate-err-msg" id="meter_serial_no-error"></span>
                            </div>
                            <div class="col-md-4">
                            <label class="col-form-label">Meter Reading&nbsp;:<span class="text-danger">*</span></label>
                                <input type="text" name="meter_reading" id="meter_reading" class="form-control" placeholder="Enter Meter Reading" value="{{ $consumer->activeMeter->initial_reading ?? '' }}" />
                                <span class="text-danger validate-err-msg" id="meter_reading-error"></span>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">Meter details not found!</div>
                    @endif
                    <div class="mb-3" id="activated-error"></div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Activate
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
@include('scripts.ajax-file-submit', ['form' => 'activated'])
