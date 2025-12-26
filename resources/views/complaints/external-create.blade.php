<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Raise Complaint</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="external-complaint-success">
                <form id="external-complaint-form" action="{{ url('externalCalls') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mt-2 p-2">
                        <h4 class="fw-semibold text-decoration-underline">Basic Details</h4>
                        <div class="row mb-2">
                            {{-- States --}}
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="col-form-label col-sm-4 text-end">State&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <select class="form-select form-select-sm" name="state_id" id="state_id" onchange="getGeoAreas(this.value)">
                                        <option value="">select</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger validate-err-msg" id="state_id-error"></span>
                                </div>
                            </div>
                            {{-- GeoAreas --}}
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="col-form-label col-sm-4 text-nowrap">Geo Area&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <select class="form-select form-select-sm" name="ga_id" id="ga_id" onchange="getDistricts(this.value)">
                                        <option value="">select</option>
                                        @foreach ($geo_areas as $ga)
                                            <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger validate-err-msg" id="ga_id-error"></span>
                                </div>
                            </div>
                            {{-- Districts --}}
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="col-form-label col-sm-4 text-end">District&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <select class="form-select form-select-sm" name="district_id" id="district_id">
                                        <option value="">select</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger validate-err-msg" id="district_id-error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="col-form-label col-sm-4 text-end">Name&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter name"/>
                                    <span class="text-danger validate-err-msg" id="name-error"></span>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="col-form-label col-sm-4 text-end">Email&nbsp;:&nbsp;</label>
                                <div class="col-sm-8">
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Enter email"/>
                                    <span class="text-danger validate-err-msg" id="email-error"></span>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="col-form-label col-sm-4 text-end">Phone&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter phone no."/>
                                    <span class="text-danger validate-err-msg" id="phone-error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 p-2">
                        <h4 class="fw-semibold text-decoration-underline">Complaint Details</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Complaint Segment --}}
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-4 text-end">Group&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <select class="form-select form-select-sm" name="segment_id" id="segment_id">
                                        <option value="">select</option>
                                        @foreach ($segments as $segment)
                                            <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger validate-err-msg" id="segment_id-error"></span>
                                </div>
                            </div>
                            {{-- Type --}}
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-4 text-end">Type&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <select class="form-select form-select-sm" name="type_id" id="type_id">
                                        <option value="">select</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger validate-err-msg" id="type_id-error"></span>
                                </div>
                            </div>
                            {{-- Priority --}}
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-4 text-end">Priority&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <select class="form-select form-select-sm" name="priority_id" id="priority_id">
                                        <option value="">select</option>
                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger validate-err-msg" id="priority_id-error"></span>
                                </div>
                            </div>
                            {{-- Media --}}
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-4 text-end">Media&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <select class="form-select form-select-sm" name="media_id" id="media_id">
                                        <option value="">select</option>
                                        @foreach ($media as $media_val)
                                            <option value="{{ $media_val->id }}">{{ $media_val->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger validate-err-msg" id="media_id-error"></span>
                                </div>
                            </div>
                            {{-- Category --}}
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-4 text-end">Category&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <select class="form-select form-select-sm" name="category_id" id="category_id" onchange="getSubCategories(this.value)">
                                        <option value="">select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger validate-err-msg" id="category_id-error"></small>
                                </div>
                            </div>
                            {{--Sub Category --}}
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-4 text-end">Sub Category&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <select class="form-select form-select-sm" name="sub_category_id" id="sub_category_id" onchange="getSubCategoryDetails(this.value)">
                                        <option value="">select</option>
                                        @foreach ($sub_categories as $s_category)
                                            <option value="{{ $s_category->id }}">{{ $s_category->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger validate-err-msg" id="sub_category_id-error"></small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-form-label col-sm-4 text-end">Notes&nbsp;:<span class="text-danger">*</span>&nbsp;</label>
                                <div class="col-sm-8">
                                    <textarea name="notes" id="notes" class="form-control"></textarea>
                                    <small class="text-danger validate-err-msg" id="notes-error"></small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="form-label col-sm-4 text-end">Documents&nbsp;:</label>
                                <div class="col-sm-8">
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="dc_file_list[]" id="dc_file_list_0" class="form-control form-control-sm">
                                        <span class="text-danger validate-err-msg" id="dc_file_list_0-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4"></label>
                                <div class="col-sm-8 mt-2">
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="dc_file_list[]" id="dc_file_list_1" class="form-control form-control-sm">
                                        <span class="text-danger validate-err-msg" id="dc_file_list_1-error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-2 bg-success-subtle d-none" id="cmp_details">
                                <div class="row g-2 mb-2">
                                    <div class="mx-5 fw-semibold text-decoration-underline">Category Details : </div>
                                    <div class="col-sm-6 text-end fw-semibold">Name : </div>
                                    <div class="col-sm-6" id="cmp_name"></div>
                                    <div class="col-sm-6 text-end fw-semibold">Resolution : </div>
                                    <div class="col-sm-6" id="cmp_resolution"></div>
                                    <div class="col-sm-6 text-end fw-semibold">Type : </div>
                                    <div class="col-sm-6" id="cmp_by"></div>
                                    <div class="col-sm-6 text-end fw-semibold">Department : </div>
                                    <div class="col-sm-6" id="cmp_dept"></div>
                                    <div class="col-sm-6 text-end fw-semibold">Est. Close At : </div>
                                    <div class="col-sm-6" id="est_close"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3" id="external-complaint-error"></div>
                    <div class="row mb-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="col-sm-2 text-end">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check2-square" aria-hidden="true">&nbsp;</i>Add Complaint
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
</script>
<script type="text/javascript">

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