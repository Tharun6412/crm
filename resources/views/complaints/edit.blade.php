{{-- Edit complaint --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Complaint - {{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="edit-complaint-success">
                <form id="edit-complaint-form" action="{{ url('calls/update/'.$complaint->id) }}" method="POST">
                    @csrf
                    <div class="mt-2">
                        <x-consumer.complaint-details :complaint="$complaint"/>
                    </div>
                    <h4>Complaint Details</h4>
                    {{-- Complaint Segment --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Segment&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm" name="segment_id" id="segment_id">
                                <option value="">select</option>
                                @foreach ($segments as $segment)
                                    <option value="{{ $segment->id }}" @selected($complaint->segment_id == $segment->id)>{{ $segment->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="segment_id-error"></span>
                        </div>
                    </div>
                    {{-- Type --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Type&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm" name="type_id" id="type_id">
                                <option value="">select</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}" @selected($complaint->type_id == $type->id)>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="type_id-error"></span>
                        </div>
                    </div>
                    {{-- Priority
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Priority&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm" name="priority_id" id="priority_id">
                                <option value="">select</option>
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority->id }}" @selected($complaint->priority_id == $priority->id)>{{ $priority->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="priority_id-error"></span>
                        </div>
                    </div> --}}
                    {{-- Media --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Media&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm" name="media_id" id="media_id">
                                <option value="">select</option>
                                @foreach ($media as $media_val)
                                    <option value="{{ $media_val->id }}" @selected($complaint->media_id == $media_val->id)>{{ $media_val->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="media_id-error"></span>
                        </div>
                    </div>
                    {{-- Category --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Category&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm" name="category_id" id="category_id" onchange="getSubCategories(this.value)">
                                <option value="">select</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($complaint->category->parent->id == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="category_id-error"></span>
                        </div>
                    </div>
                    {{--Sub Category --}}
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Sub Category&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm" name="sub_category_id" id="sub_category_id" onchange="getSubCategoryDetails(this.value)">
                                <option value="">select</option>
                                @foreach ($sub_categories as $s_category)
                                    <option value="{{ $s_category->id }}" @selected($complaint->category_id == $s_category->id)>{{$s_category->priority->name}} - {{ $s_category->type->name }} - {{ $s_category->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="sub_category_id-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="offset-sm-3 col-sm-7">
                            <div class="border border-info rounded d-none" id="cmp_details">
                                <div class="row g-2 pb-2 mb-2">
                                    <div class="col-sm-6 text-end fw-semibold">PNGRB Category : </div>
                                    <div class="col-sm-6" id="cmp_priority"></div>
                                    <div class="col-sm-6 text-end fw-semibold">PNGRB Type : </div>
                                    <div class="col-sm-6" id="cmp_by"></div>
                                    <div class="col-sm-6 text-end fw-semibold">Resolution : </div>
                                    <div class="col-sm-6" id="cmp_resolution"></div>
                                    <div class="col-sm-6 text-end fw-semibold">Est. Close At : </div>
                                    <div class="col-sm-6" id="est_close"></div>
                                    <div class="col-sm-6 text-end fw-semibold">Department : </div>
                                    <div class="col-sm-6" id="cmp_dept"></div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                            <span class="text-danger validate-err-msg" id="notes-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Irregularities&nbsp;:</label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm" name="irregularities_id" id="irregularities_id">
                                <option value="">select</option>
                                @foreach ($irregularities as $item)
                                    <option value="{{ $item->id }}" @selected($complaint->irregularities_id == $item->id)>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger validate-err-msg" id="irregularities_id-error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 text-end fw-semibold"><i class="bi bi-paperclip"></i>Current Documents : </div>
                        <div class="col-sm-7">
                            @foreach ($complaint->complaintDocuments as $document)
                                <span id="doc-{{ $document->file_id }}">
                                    <a href="{{ url('dc/documents/' . $document->file_id) }}" title="{{ $document->file->file_name }}" target="_blank" class="btn btn-info btn-sm"><i class="bi bi-file-earmark-pdf"></i></a>        
                                    <a type="button" onclick="deleteComplaintDoc({{ $document->file_id }})" title="{{ $document->file->file_name }}" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>&nbsp;&nbsp;       
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <label class="form-label col-sm-3 text-end">Documents&nbsp;:</label>
                        <div class="col-sm-7">
                            <div class="input-group input-group-sm mb-1">
                                <input type="file" name="dc_file_list[]" id="dc_file_list_0" class="form-control form-control-sm">
                                <span class="text-danger validate-err-msg" id="dc_file_list_0-error"></span>
                            </div>
                            <div class="input-group input-group-sm">
                                <input type="file" name="dc_file_list[]" id="dc_file_list_1" class="form-control form-control-sm">
                                <span class="text-danger validate-err-msg" id="dc_file_list_1-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row my-1" id="edit-complaint-error"></div>
                    <div class="row">
                        <div class="offset-md-3 col-sm-9">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save" aria-hidden="true">&nbsp;</i>Update
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
@include('scripts.ajax-file-submit', ['form' => 'edit-complaint'])
<script type="text/javascript">
    $(document).ready(function() {
        getSubCategoryDetails($('#sub_category_id').val());
    });
    //Get Sub Categories By Id  
    function getSubCategories(category_id){
        $('#sub_category_id').empty();
        let options = '<option value="">select</option>';
        $.get("{{ url('calls/getSubCategories') }}", {'category_id' : category_id}, function(data) {
            if(data.sub_categories && data.sub_categories.length > 0) {
                data.sub_categories.forEach(function(category) {
                    let priority = category.priority ? ` ${category.priority.name}` : ''; 
                    let type = category.type ? ` ${category.type.name}` : '';
                    options += `<option value="${category.id}">${priority} - ${type} - ${category.name}</option>`;
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
            $('#cmp_priority').html(data.category_details.priority.name);
            $('#cmp_by').html(data.category_details.type.name);
            $('#est_close').html(data.estimation_time);
        });
    }

    // Delete Docuement
    function deleteComplaintDoc(doc_id)
    {
        if(confirm("Are you sure you want to delete the document")) {
            $.post("{{ url('calls/deleteComplaintDocument') }}/"+doc_id, {_token:"{{ csrf_token() }}"}, function(data) {
                // Response
                if(data.status == 1) {
                    $('#doc-'+doc_id).remove();
                }
            }).fail(function() {
                alert("Document Deletion Failed");
            });
        }
    }
</script>