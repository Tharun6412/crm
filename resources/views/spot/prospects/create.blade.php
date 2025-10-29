{{-- Add Prospect Form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Prospect Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="add-prospect-success">
                <form action="{{ url('spot/prospects') }}" method="POST" id="add-prospect-form">
                    @csrf
                    <div class="row mb-2">
                        <label for="ga_id" class="col-sm-3 col-form-label text-end">Geo Area<span>&nbsp;:</span></label>
                        <div class="col-sm-8">
                            <select name='ga_id' id='ga_id' class="form-select" onchange="getIndustrialAreaByGA(this.value)">
                                <option value=''>Select GA</option>
                                @foreach($geo_areas as $ga)
                                    <option value='{{ $ga->id }}'>{{ $ga->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="ga_id" class="col-sm-3 col-form-label text-end">Segment<span>&nbsp;:</span></label>
                        <div class="col-sm-8">
                            <select name='segment_id' id='segment_id' class="form-select">
                                <option value=''>Select Segment</option>
                                @foreach($segments as $segment)
                                    <option value='{{ $segment->id }}'>{{ $segment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="firm_id" class="col-sm-3 col-form-label text-end">Firm Type<span>&nbsp;:</span></label>
                        <div class="col-sm-8">
                            <select name='firm_id' id='firm_id' class="form-select">
                                <option value=''>Select Firm Type</option>
                                @foreach($firm_types as $type)
                                    <option value='{{ $type->id }}'>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="fuel_id" class="col-sm-3 col-form-label text-end">Fuel Type<span>&nbsp;:</span></label>
                        <div class="col-sm-8">
                            <select name='fuel_id' id='fuel_id' class="form-select">
                                <option value=''>Select Fuel Type</option>
                                @foreach($fuel_types as $fuel_type)
                                    <option value='{{ $fuel_type->id }}'>{{ $fuel_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="industrial_area_id" class="col-sm-3 col-form-label text-end">Industrial Area<span>&nbsp;:</span></label>
                        <div class="col-sm-8">
                            <select name='industrial_area_id' id='industrial_area_id' class="form-select">
                                <option value=''>Select Industrial Area</option>
                                @foreach($industrial_areas as $area)
                                    <option value='{{ $area->id }}'>{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="name" class="col-sm-3 col-form-label text-end">Name<span>&nbsp;:</span></label>
                        <div class="col-sm-8">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="zone" class="col-sm-3 col-form-label text-end">Zone<span>&nbsp;:</span></label>
                        <div class="col-sm-8">
                            <input type="text" name="zone" id="zone" class="form-control" placeholder="Enter Zone">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="fuel_consumption" class="col-sm-3 col-form-label text-end">Fuel Consumption<span>&nbsp;:</span></label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" name="fuel_consumption" id="fuel_consumption" class="form-control form-control-sm" value="" placeholder="Enter Fuel Consumption">
                                <select name="unit_id" id="unit_id" class="form-select form-select-sm">
                                    <option value="">Select Unit</option>
                                    <option value="1">Liters</option>
                                    <option value="2">Kgs</option>
                                    <option value="3">Tons</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2"> 
                        <label for="potential" class="col-sm-3 col-form-label text-end">Total Potential<span>&nbsp;:</span></label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" name="potential" id="potential" class="form-control form-control-sm" value="" placeholder="Enter Potential">
                                <span class="input-group-text">SCMD</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Expected date&nbsp;<span class="error text-danger"></span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" name="expected_date" id="expected_date" class="form-control" value="" placeholder="DD-MM-YYYY">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Latitude<span class="error text-danger"></span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" name="latitude" id="latitude" class="form-control form-control-sm" value="" placeholder="Enter Latitude">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Longitude<span class="error text-danger"></span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" name="longitude" id="longitude" class="form-control form-control-sm" value="" placeholder="Enter Longitude">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">NG Pipeline available at the Industrial gate?&nbsp;<span class="error text-danger"></span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pipeline_availability" id="pipeline_availability_1" value="1" onclick="pipelineCheck(this.value)">
                                <label class="form-check-label" for="pipeline_availability_1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pipeline_availability" id="pipeline_availability_2" value="2" onclick="pipelineCheck(this.value)">
                                <label class="form-check-label" for="pipeline_availability_2">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 d-none" id="steel_pipeline_div">
                        <label class="col-form-label col-sm-3 text-end">Steel Pipeline&nbsp;<span class="error text-danger"></span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" name="steel_pipeline" id="steel_pipeline" class="form-control form-control-sm" value="" placeholder="Enter Steel Pipeline in Kms">
                                <span class="input-group-text">Kms</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 d-none" id="mdpe_pipeline_div">
                        <label class="col-form-label col-sm-3 text-end">MDPE Pipeline&nbsp;<span class="error text-danger"></span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" name="mdpe_pipeline" id="mdpe_pipeline" class="form-control form-control-sm" value="" placeholder="Enter MDPE Pipeline in Kms">
                                <span class="input-group-text">Kms</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Notes&nbsp;<span class="error text-danger"></span>&nbsp;:</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <textarea name="notes" id="notes" class="form-control form-control-sm" placeholder="Enter Notes"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="add-prospect-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-8">
                            <button type="submit" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp;Add Prospect</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
{{-- Load JS Files --}}
@include('scripts.ajax-form-submit', ['form' => 'add-prospect'])
<script type="text/javascript">
    $(function(){
        $('#expected_date').datepicker({format : 'dd-mm-yyyy', startDate:'today', autoHide :true});

        // Pipeline Availability Check
        var pipeline = $("input[name='pipeline_availability']:checked").val()
        if (pipeline == 2) {
            $('#steel_pipeline_div').removeClass('d-none');
            $('#mdpe_pipeline_div').removeClass('d-none');
        }
        else {
            $('#steel_pipeline_div').addClass('d-none');
            $('#mdpe_pipeline_div').addClass('d-none');
        }
    });
    // PipeLine Check Function
    function  pipelineCheck(val) {
        if (val == 2) {
            $('#steel_pipeline_div').removeClass('d-none');
            $('#mdpe_pipeline_div').removeClass('d-none');
        }
        else {
            $('#steel_pipeline_div').addClass('d-none');
            $('#mdpe_pipeline_div').addClass('d-none');
        }
    }
</script>