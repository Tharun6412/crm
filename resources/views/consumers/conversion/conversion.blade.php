{{-- Postpaid to prepaid conversion Form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Conversion</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Check outstanding balance --}}
            @if ($os_balance['balance'] > 0)
                <div class="alert alert-danger">
                    Consumer cannot be converted at this time. Outstanding dues must be cleared before proceeding.
                </div>
            @else
                <div id="conversion-success">
                    <form id="conversion-form" action="{{ url('consumers/conversion/' . $consumer->id) }}">
                        @csrf
                        @method('PUT')
                        <x-consumer.basic-details :consumer="$consumer" :type="2" class="bg-info-subtle" />
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-square-fill fs-5"></i>&nbsp;Outstanding balances are cleared!
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 text-end fs-5 fw-bold">Closing bill details</div>
                            <div class="col-sm-8"></div>
                            <label class="col-sm-4 col-form-label text-end">Bill date:</label>
                            <div class="col-6">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="bill_date" id="bill_date" class="form-control" placeholder="DD-MM-YYYY"/>
                                    <label for="bill_date" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                                </div>
                            </div>
                            <label class="col-sm-4 col-form-label text-end">Consumption:</label>
                            <div class="col-6">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="bill_qty" id="bill_qty" class="form-control"/>
                                    <label for="bill_qty" class="input-group-text">SCM</label>
                                </div>
                            </div>
                            <label class="col-sm-4 col-form-label text-end">Bill amount:</label>
                            <div class="col-6">
                                <input type="text" name="bill_amount" id="bill_amount" class="form-control form-control-sm"/>
                            </div>
                            <label class="col-sm-4 col-form-label text-end">Bill Status:</label>
                            <div class="col-6">
                                <select name="bill_status" id="bill_status" class="form-select form-select-sm">
                                    <option value="">Select Status</option>
                                    <option value="1">Paid</option>
                                    <option value="0">Pending</option>
                                </select>
                            </div>
                            <div class="col-sm-4 text-end fs-5 fw-bold">Prepaid Scheme</div>
                            <div class="col-sm-8"></div>
                            <label class="col-sm-4 col-form-label text-end">New prepaid scheme:</label>
                            <div class="col-6">
                                <select name="new_scheme" id="new_scheme" class="form-select form-select-sm">
                                    <option value="">Select Scheme</option>
                                    @foreach ($ga_schemes as $scheme)
                                        <option value="{{ $scheme->id }}">{{ $scheme->code }} - {{ $scheme->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Meter details --}}
                            <div class="col-sm-4 text-end fs-5 fw-bold">Smart Meter Details</div>
                            <div class="col-sm-8"></div>
                            <label class="col-sm-4 col-form-label text-end">Meter No.:</label>
                            <div class="col-6">
                                <input type="text" name="meter_no" id="meter_no" class="form-control form-control-sm"/>
                            </div>
                            <label class="col-sm-4 col-form-label text-end">Meter Sr. No.:</label>
                            <div class="col-6">
                                <input type="text" name="meter_sno" id="meter_sno" class="form-control form-control-sm"/>
                            </div>
                            <label class="col-sm-4 col-form-label text-end">Meter Initial reading:</label>
                            <div class="col-6">
                               <div class="input-group input-group-sm">
                                    <input type="text" name="meter_reading" id="meter_reading" class="form-control"/>
                                    <label for="meter_reading" class="input-group-text">SCM</label>
                               </div>
                            </div>
                            <div class="offset-sm-4 col-sm-6"><hr></div>
                            <label class="col-sm-4 col-form-label text-end">Notes:</label>
                            <div class="col-6">
                                <textarea name="notes" id="notes" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="m-1" id="conversion-error"></div>
                        <div class="row">
                            <div class="offset-sm-4 col-sm-6">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save" aria-hidden="true">&nbsp;</i>Convert
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.datepicker', ['list' => ['bill_date']])
@include('scripts.ajax-form-submit', ['form' => 'conversion'])
