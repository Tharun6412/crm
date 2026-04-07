<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Meter Change</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer basic details --}}
            <x-consumer.basic-details :consumer="$consumer_meter->consumer" class="bg-info-subtle" />
                {{-- Meter Change Form --}}
            @if ($consumer_meter?->oldMeter?->status_id == 1)
                <div class="mt-3 alert alert-danger">
                    <span>Current Meter still in pending status. New Meter cannot be created.</span>
                    @php
                        exit;
                    @endphp
                </div>
            @endif
            <div class="mt-2" id="meter-change-success">
                <form id="meter-change-form" action="{{ url('consumers/meterChange/'.$id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-secondary-subtle p-2 fw-semibold text-dark rounded-2">Current Meter Details</div>
                    <div class="row g-2 pt-2">
                        <div class="col-sm-2 text-end fw-semibold">Meter Number : </div>
                        <div class="col-sm-4">{{ $consumer_meter->meter_no }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Meter Serial Number : </div>
                        <div class="col-sm-3">{{ $consumer_meter->meter_serial_no }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Meter Status : </div>
                        <div class="col-sm-4">{{ $consumer_meter->meterStatus->name }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Install Date : </div>
                        <div class="col-sm-3">{{ $consumer_meter?->install_date?->format('d-m-Y') }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Installed By : </div>
                        <div class="col-sm-4">{{ $consumer_meter?->installBy?->name }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Previous Reading<span class="text-danger">*</span>&nbsp;:</div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="prev_reading" id="prev_reading" class="form-control text-satrt"/>
                                <span class="input-group-text">SCM</span>
                            </div>
                            <span class="text-danger validate-err-msg" id="prev_reading-error"></span>
                        </div>
                        <div class="col-sm-2 text-end fw-semibold">Document<span class="text-danger">*</span>&nbsp;:</div>
                        <div class="col-sm-4">
                            <input type="file" name="dc_file" id="dc_file" class="form-control"/>
                            <span class="text-danger validate-err-msg" id="dc_file-error"></span>
                        </div>
                        <div class="col-sm-3 text-end fw-semibold">End Reading<span class="text-danger">*</span>&nbsp;:</div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="end_reading" id="end_reading" class="form-control text-satrt"/>
                                <span class="input-group-text">SCM</span>
                            </div>
                            <span class="text-danger validate-err-msg" id="end_reading-error"></span>
                        </div>
                    </div>
                    <br/>
                    <div class="bg-secondary-subtle p-2 fw-semibold text-dark rounded-2">New Meter Details</div>
                    <div class="row mb-2">  
                        <div class="col-md-4">
                            <label class="col-form-label">Meter Number<span class="text-danger">*</span>&nbsp;:</label>
                            <input type="text" name="meter_no" id="meter_no" class="form-control"/>
                            <span class="text-danger validate-err-msg" id="meter_no-error"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label">Meter Serial Number&nbsp;:</label>
                            <input type="text" name="meter_serial_no" id="meter_serial_no" class="form-control"/>
                            <span class="text-danger validate-err-msg" id="meter_serial_no-error"></span>
                        </div>
                        <div class="col-md-4">
                        <label class="col-form-label">Initial Reading<span class="text-danger">*</span>&nbsp;:</label>
                            <input type="text" name="initial_reading" id="initial_reading" class="form-control"/>
                            <span class="text-danger validate-err-msg" id="initial_reading-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label class="form-label" for="request_date">Request Date&nbsp;:&nbsp;</label>
                            <div class="input-group">
                                <input name="request_date" id="request_date" class="form-control" placeholder="Request Date( DD-MM-YYYY )" type="text"/>
                                <span class="input-group-text"><i class="bi bi-calendar2-event"></i></span>
                            </div>
                            <span class="text-danger validate-err-msg" id="request_date-error"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="release_date">Replace Date&nbsp;:&nbsp;</label>
                            <div class="input-group">
                                <input name="release_date" id="release_date" class="form-control" placeholder="Release Date( DD-MM-YYYY )" type="text"/>
                                <span class="input-group-text"><i class="bi bi-calendar2-event"></i></span>
                            </div>
                            <span class="text-danger validate-err-msg" id="release_date-error"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label">Technician<span class="text-danger">*</span>&nbsp;:</label>
                            <select name="technician_id" id="technician_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}&nbsp;({{ $user->emp_id }})</option>                                    
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="technician_id-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="col-form-label">Reason&nbsp;:<span class="text-danger">*</span></label>
                            <textarea class="form-control" name="reason" id="reason"></textarea>
                            <span class="text-danger validate-err-msg" id="reason-error"></span>
                        </div>
                    </div>
                    {{-- <div class="text-danger mt-3" id="meter-change-error"></div> --}}
                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Add Details
                                </button>
                            </div>
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
@include('scripts.datepicker', ['list' => ['request_date', 'release_date']])
@include('scripts.ajax-file-submit', ['form' => 'meter-change'])
