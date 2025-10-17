{{-- Module create, modal view --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Module</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="mod-add-success">
            <form action="{{ url('admin/modules') }}" method="POST" id="mod-add-form">
                @csrf
                @isset($parent)
                    <input type="hidden" name="parent_id" value="{{ $parent }}">
                @endisset
                <div class="row mb-2">
                    <label for="name" class="col-sm-2 col-form-label text-end">Module Name</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="package_id" class="col-sm-2 col-form-label text-end">Package</label>
                    <div class="col-sm-9">
                        <select name="package_id" id="package_id" class="form-select">
                            <option value="">Select package</option>
                            @foreach ($packages as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="url" class="col-sm-2 col-form-label text-end">Landing URL</label>
                    <div class="col-sm-9">
                        <input type="text" name="url" id="url" class="form-control">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="code" class="col-sm-2 col-form-label text-end">Slug</label>
                    <div class="col-sm-9">
                        <input type="text" name="code" id="code" class="form-control">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="icon" class="col-sm-2 col-form-label text-end">Icon</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <label for="" class="input-group-text"><i id="m-icon" class="bi bi-plus-circle-dotted"></i></label>
                            <input type="text" name="icon" id="icon" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="status" class="col-sm-2 col-form-label text-end">Status</label>
                    <div class="col-sm-9">
                        <select name="status" id="status" class="form-select">
                            <option value="1">Enable</option>
                            <option value="0">Disable</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="position" class="col-sm-2 col-form-label text-end">Position</label>
                    <div class="col-sm-9">
                        <input type="text" name="position" id="position" class="form-control">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="parent" class="col-sm-2 col-form-label text-end">Parent</label>
                    <div class="col-sm-9">
                        <label class="col-form-label">{{ $parent }}</label>
                    </div>
                </div>
                <div id="mod-add-error"></div>
                <div class="row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp;Add module</button>
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
@include('scripts.ajax-form-submit', ['form' => 'mod-add'])
<script>
    $(function(){
        // Icon
        $("#icon").on('keyup', function(e){
            $("#m-icon").removeClass();
            $("#m-icon").addClass('bi ' + $(this).val());
        });
    });
</script>