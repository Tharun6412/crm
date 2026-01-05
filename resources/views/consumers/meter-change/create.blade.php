<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Meter Change</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer basic details --}}
            <x-consumer.basic-details :consumer="$consumer_meter->consumer" class="bg-info-subtle" />
                {{-- Meter Change Form --}}
            <div class="mt-2" id="meter-change-success">
                <form id="meter-change-form" action="{{ url('consumers/meterChange/'.$id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="fw-semibold text-decoration-underline">Current Meter Details</div>
                    <div class="row g-2">
                        <div class="col-sm-3 text-end fw-semibold">Meter Number : </div>
                        <div class="col-sm-3">{{ $consumer_meter->meter_no }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Meter Serial Number : </div>
                        <div class="col-sm-3">{{ $consumer_meter->meter_serial_no }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Meter Status : </div>
                        <div class="col-sm-3">{{ $consumer_meter->meterStatus->name }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Install Date : </div>
                        <div class="col-sm-3">{{ $consumer_meter->install_date->format('d-m-Y') }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Installed By : </div>
                        <div class="col-sm-3">{{ $consumer_meter->installBy->name }}</div>
                        <div class="col-sm-3 text-end fw-semibold">Previous Reading : </div>
                        <div class="col-sm-3">{{ round(($consumer_meter->meterConsumption?->prev_reading ?? $consumer_meter->initial_reading), 3) }}</div>
                        <div class="col-sm-6"></div>
                        <div class="col-sm-3 text-end fw-semibold">End Reading&nbsp;:<span class="text-danger">*</span></div>
                        <div class="col-sm-3">
                            <input type="text" name="end_reading" id="end_reading" class="form-control form-control-sm text-satrt"/>
                        </div>
                    </div>
                    <br/>
                    <div class="fw-semibold text-decoration-underline">New Meter Details</div>
                    <div class="row mb-2">  
                        <div class="col-md-4">
                            <label class="col-form-label">Meter Number&nbsp;:<span class="text-danger">*</span></label>
                            <input type="text" name="meter_no" id="meter_no" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label">Meter Serial Number&nbsp;:</label>
                            <input type="text" name="meter_serial_no" id="meter_serial_no" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-4">
                        <label class="col-form-label">Initial Reading&nbsp;:<span class="text-danger">*</span></label>
                            <input type="text" name="initial_reading" id="initial_reading" class="form-control form-control-sm"/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label" for="request_date">Request Date&nbsp;:&nbsp;</label>
                            <div class="input-group input-group-sm">
                                <input name="request_date" id="request_date" class="form-control form-control-sm" placeholder="Request Date( DD-MM-YYYY )" type="text"/>
                                <span class="input-group-text"><i class="bi bi-calendar2-event"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="release_date">Release Date&nbsp;:&nbsp;</label>
                            <div class="input-group input-group-sm">
                                <input name="release_date" id="release_date" class="form-control form-control-sm" placeholder="Release Date( DD-MM-YYYY )" type="text"/>
                                <span class="input-group-text"><i class="bi bi-calendar2-event"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="col-form-label">Technician</label>
                            <select name="technician_id" id="technician_id" class="form-select form-select-sm">
                                <option>All</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}&nbsp;({{ $user->emp_id }})</option>                                    
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="col-form-label">Reason&nbsp;:<span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-sm" name="reason" id="reason"></textarea>
                        </div>
                    </div>
                    <div class="text-danger mt-3" id="meter-change-error"></div>
                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Update
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
@include('scripts.ajax-form-submit', ['form' => 'meter-change'])
