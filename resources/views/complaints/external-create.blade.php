{{-- Create external complaint --}}
@extends('layouts.layout')

@section('title', 'Raise an External Complaint')

@section('page-title', 'Raise an External Complaint')

@section('page-content')
    <div class="container-fluid mt-3 bg-white border">
        <div id="external-complaint-success" class="p-3">
            <form id="external-complaint-form" action="{{ url('calls/external') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <h4 class="text-primary">Person Details</h4>
                    <div class="row g-3">
                        <div class="col">     
                            <div class="input-group">
                                <label for="state_id" class="input-group-text">State<span class="text-danger">*</span></label>
                                <select class="form-select" name="state_id" id="state_id" onchange="getGeoAreas(this.value)">
                                    <option value="">select</option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger validate-err-msg" id="state_id-error"></span>
                        </div>
                        <div class="col">    
                            <div class="input-group">    
                                <label for="ga_id" class="input-group-text">GA<span class="text-danger">*</span></label>
                                <select class="form-select" name="ga_id" id="ga_id" onchange="getDistricts(this.value)">
                                    <option value="">select</option>
                                    @foreach ($geo_areas as $ga)
                                        <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger validate-err-msg" id="ga_id-error"></span>
                        </div>
                        <div class="col">    
                            <div class="input-group">    
                                <label for="district_id" class="input-group-text">District<span class="text-danger">*</span></label>
                                <select class="form-select" name="district_id" id="district_id">
                                    <option value="">select</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger validate-err-msg" id="district_id-error"></span>
                        </div>
                    </div>  
                    <div class="row g-2 mt-1">
                        <div class="col">
                            <label class="col-form-label">Name&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name"/>
                            <span class="text-danger validate-err-msg" id="name-error"></span>
                        </div>
                        <div class="col">
                            <label class="col-form-label">Phone&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone"/>
                            <span class="text-danger validate-err-msg" id="phone-error"></span>
                        </div>
                        <div class="col">
                            <label class="col-form-label">Email&nbsp;:&nbsp;</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Email"/>
                            <span class="text-danger validate-err-msg" id="email-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <h4 class="text-primary">Complaint Details</h4>
                </div>
                {{-- Complaint Segment --}}
                <div class="row">
                    <div class="col">
                        <label class="col-form-label">Segment&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                        <select class="form-select" name="segment_id" id="segment_id">
                            <option value="">select</option>
                            @foreach ($segments as $segment)
                                <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger validate-err-msg" id="segment_id-error"></span>
                    </div>
                    <div class="col">
                        {{-- Type --}}
                        <label class="col-form-label">Type&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                        <select class="form-select" name="type_id" id="type_id">
                            <option value="">select</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger validate-err-msg" id="type_id-error"></span>
                    </div>
                    {{-- Priority --}}
                    <div class="col">
                        <label class="col-form-label">Priority&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                        <select class="form-select" name="priority_id" id="priority_id">
                            <option value="">select</option>
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger validate-err-msg" id="priority_id-error"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">    
                        {{-- Media --}}
                        <label class="col-form-label">Media&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                        <select class="form-select" name="media_id" id="media_id">
                            <option value="">select</option>
                            @foreach ($media as $media_val)
                                <option value="{{ $media_val->id }}">{{ $media_val->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger validate-err-msg" id="media_id-error"></span>
                    </div>
                    <div class="col">
                        {{-- Category --}}
                        <label class="col-form-label text-end">Category&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                        <select class="form-select" name="category_id" id="category_id" onchange="getSubCategories(this.value)">
                            <option value="">select</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger validate-err-msg" id="category_id-error"></small>
                    </div>
                    <div class="col">
                        {{--Sub Category --}}
                        <label class="col-form-label">Sub Category&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                        <select class="form-select" name="sub_category_id" id="sub_category_id" onchange="getSubCategoryDetails(this.value)">
                            <option value="">select</option>
                            @foreach ($sub_categories as $s_category)
                                <option value="{{ $s_category->id }}">{{ $s_category->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger validate-err-msg" id="sub_category_id-error"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="col-form-label">Notes&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
                        <div class="form-text">Maximum 255 characters allowed.</div>
                        <small class="text-danger validate-err-msg" id="notes-error"></small>
                    </div>
                    <div class="col-4 ms-auto">        
                    {{-- Notes --}}
                        <div class="mt-3">
                            <table class="table table-bordered table-striped d-none table-info" id="cmp_details">
                                <tr>
                                    <td>Resolution</td>
                                    <td>
                                        <div id="cmp_resolution"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>
                                        <div id="cmp_by"></div>
                                    </td>
                                </tr>                                
                                <tr>
                                    <td>Department</td>
                                    <td>
                                        <div id="cmp_dept"></div>
                                    </td>
                                </tr>                                                                
                                <tr>
                                    <td>Est. Close At</td>
                                    <td>
                                        <div id="est_close"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-6">
                        <label class="form-labe">Documents&nbsp;:</label>
                        <div class="input-group my-2">
                            <input type="file" name="dc_file_list[]" id="dc_file_list_0" class="form-control">
                            <span class="text-danger validate-err-msg" id="dc_file_list_0-error"></span>
                        </div>
                        <div class="input-group">
                            <input type="file" name="dc_file_list[]" id="dc_file_list_1" class="form-control">
                            <span class="text-danger validate-err-msg" id="dc_file_list_1-error"></span>
                        </div>
                        <div class="mb-2" id="external-complaint-error"></div>
                    </div>
                </div>  
                <div class="row mt-3">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-lg" aria-hidden="true">&nbsp;</i>Add External Complaint
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@include('scripts.ajax-file-submit', ['form' => 'external-complaint'])
<script type="text/javascript">
    // Get Areas By Charge Area
    function getGeoAreas(state_id) {
        $.get("{{ url('common/stateGas') }}", { 'state_id' :state_id }, function(data) {
            $('#ga_id').empty();
            let options = '<option value="">Select area</option>'
            if(data.geo_areas && data.geo_areas.length > 0) {
                data.geo_areas.forEach(function(value) {
                    options += `<option value="${value.id}">${value.name}</option>`;
                });
            }
            $('#ga_id').html(options);
        });
    }
    // Get Districts By GA
    function getDistricts(ga) 
    {
        $.get("{{ url('common/gaDistrictsSchemes') }}", { 'ga_id' : ga }, function(data) {
            $('#district_id').empty();
            let options = '<option value = "">Select district</option>'
            if(data.districts && data.districts.length > 0) {
                data.districts.forEach(function(dist) {
                    options += `<option value="${dist.id}">${dist.name}</option>`;
                });
            }
            $('#district_id').html(options);
        });
    }
    //Get Sub Categories By Id  
    function getSubCategories(category_id)
    {
        $('#sub_category_id').empty();
        let options = '<option value="">select</option>';
        $.get("{{ url('calls/getSubCategories') }}", {'category_id' : category_id}, function(data) {
            if(data.sub_categories && data.sub_categories.length > 0) {
                data.sub_categories.forEach(function(category) {
                    options += `<option value="${category.id}">${category.name}</option>`;
                });
            }
            $('#sub_category_id').html(options);
        });
    }
    // Get Sub Category Details
    function getSubCategoryDetails(category_id)
    {
        $('#cmp_details').removeClass('d-none');
        $.get("{{ url('calls/getSubCategoryDetails') }}", {'sub_category_id' : category_id}, function(data) {
            $('#cmp_name').html(data.category_details.name);
            $('#cmp_resolution').html(data.category_details.resolution + " " + (data.category_details.resolution_type == 1 ? "Days" : "Hours"));
            $('#cmp_dept').html(data.category_details.department.name);
            $('#cmp_by').html(data.category_details.type.name);
            $('#est_close').html(data.estimation_time);
        });
    }

    // Get Scheme Details
    function getSchemeDetails(scheme_id)
    {
        $.get("{{ url('common/schemeDetails') }}", {'scheme_id' : scheme_id}, function(data) {
            if(data.scheme_details != null) {
                $('#scheme_name_details').html(data.scheme_details.name);
                $('#security').html(data.scheme_details.security);
                $('#consumption').html(data.scheme_details.consumption);
                $('#registration').html(data.scheme_details.registration);
                $('#emi_amount').html(data.scheme_details.emi_amount);
                $('#rental_amount').html(data.scheme_details.rental_amount);
                $('#scheme_data').removeClass('d-none');
            }
            else {
                $('#scheme_data').addClass('d-none');
            }
        });
    }
</script>