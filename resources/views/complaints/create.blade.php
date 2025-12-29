<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Raise Complaint</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="complaint-success">
                <form id="complaint-form" action="{{ url('calls/store/1') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mt-2">
                        <x-consumer.basic-details :consumer="$consumer" class="bg-info-subtle"/>
                    </div>
                    <div class="mt-2 p-2">
                        <h4>Complaint Form</h4>
                    </div>
                    {{-- Complaint Segment --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Segment&nbsp;:<span class="text-danger">*</span></label>
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
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Type&nbsp;:<span class="text-danger">*</span></label>
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
                    {{-- Media --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Media&nbsp;:<span class="text-danger">*</span></label>
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
                    {{-- Priority --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Priority&nbsp;:<span class="text-danger">*</span></label>
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
                    {{-- Category --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Category&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select form-select-sm" name="category_id" id="category_id" onchange="getSubCategories(this.value)">
                                <option value="">select</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="category_id-error"></span>
                        </div>
                    </div>
                    {{--Sub Category --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Sub Category&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select form-select-sm" name="sub_category_id" id="sub_category_id" onchange="getSubCategoryDetails(this.value)">
                                <option value="">select</option>
                                @foreach ($sub_categories as $s_category)
                                    <option value="{{ $s_category->id }}">{{ $s_category->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="sub_category_id-error"></span>
                            <div class="mt-2 border border-info rounded d-none" id="cmp_details">
                                <div class="row g-2">
                                    <div class="col-sm-4 text-end fw-semibold">Resolution : </div>
                                    <div class="col-sm-6" id="cmp_resolution"></div>
                                    <div class="col-sm-4 text-end fw-semibold">Type : </div>
                                    <div class="col-sm-6" id="cmp_by"></div>
                                    <div class="col-sm-4 text-end fw-semibold">Department : </div>
                                    <div class="col-sm-6" id="cmp_dept"></div>
                                    <div class="col-sm-4 text-end fw-semibold">Est. Close At : </div>
                                    <div class="col-sm-6" id="est_close"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <div class="form-text">Maximum 255 characters allowed.</div>
                            <span class="text-danger validate-err-msg" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="form-label col-sm-3 text-end">Documents&nbsp;:</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="file" name="dc_file_list[]" id="dc_file_list_0" class="form-control form-control-sm">
                                <span class="text-danger validate-err-msg" id="dc_file_list_0-error"></span>
                            </div>
                        </div>
                        <label class="col-sm-3"></label>
                        <div class="col-sm-8 mt-2">
                            <div class="input-group input-group-sm">
                                <input type="file" name="dc_file_list[]" id="dc_file_list_1" class="form-control form-control-sm">
                                <span class="text-danger validate-err-msg" id="dc_file_list_1-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2" id="complaint-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-9">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-lg" aria-hidden="true">&nbsp;</i>Raise Complaint
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
@include('scripts.ajax-file-submit', ['form' => 'complaint'])
<script type="text/javascript">
    //Get Sub Categories By Id  
    function getSubCategories(category_id) {
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
    function getSubCategoryDetails(category_id) {
        $('#cmp_details').removeClass('d-none');
        $.get("{{ url('calls/getSubCategoryDetails') }}", {'sub_category_id' : category_id}, function(data) {
            $('#cmp_name').html(data.category_details.name);
            $('#cmp_resolution').html(data.category_details.resolution + " " + (data.category_details.resolution_type == 1 ? "Days" : "Hours"));
            $('#cmp_dept').html(data.category_details.department.name);
            $('#cmp_by').html(data.category_details.type.name);
            $('#est_close').html(data.estimation_time);
        }).fail(function(response){
            $('#cmp_details').addClass('d-none');
        });;
    }
</script>
