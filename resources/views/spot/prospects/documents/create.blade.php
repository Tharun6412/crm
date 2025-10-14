<div class="bd-callout bd-callout-success bg-transparent card mt-0 border-success mb-3" id="add-doc-success">
    <h4>Add Document&nbsp;:</h4>
    <form action="{{ url('spot/prospectDocument/store/'.$id) }}" method="POST" id="add-doc-form" enctype="multipart/form-data">
        @csrf
        <div class="row mb-1">
            <label class="col-form-label text-end col-sm-3 text-black">Document Type&nbsp;<span class="text-danger">*</span>&nbsp;:</label>
            <div class="col-sm-6">
                <select id="document_type_id" name="document_type_id" class="form-select form-select-sm">
                    <option value="">select Document Type</option>
                        @foreach ($document_types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                </select>
                <span class="text-danger" id="document_type_id-error"></span>
            </div>
        </div>
        <div class="row mb-1">
            <label class="col-form-label text-end col-sm-3 text-black">Document&nbsp;<span class="text-danger">*</span>:</label>
            <div class="col-sm-6">
                <input type="file" id='dc_file' name="dc_file" class="form-control form-control-sm">
                <span class="text-danger" id="dc_file-error"></span>
            </div>
        </div>
        <div class="row">
            <label class="col-form-label text-end col-sm-3">&nbsp;</label>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-upload"></i>&nbsp;Upload</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="$('#action-type').html('')"><i class="bi bi-x"></i>&nbsp;Cancel</button>
            </div>
        </div>
    </form>
</div>
@include('scripts.ajax-file-submit', ['form' => 'add-doc', 'callback' => 'reloadDocForm()'])
<script type="text/javascript">
    function reloadDocForm()
    {
        $.get($('#reload-form').attr('href'), function(data) {
            $('#prospect-documents').html(data);
        });
    }
</script>

