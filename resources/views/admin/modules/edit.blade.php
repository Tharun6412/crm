{{-- Module edit, modal view --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Module - {{ $module->name }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="mod-edit-success">
            <form action="{{ url('admin/modules/' . $module->id) }}" method="POST" id="mod-edit-form">
                @csrf
                @method('PUT')
                <div class="row mb-2">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" id="name" class="form-control" value="{{ $module->name }}">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="package_id" class="col-sm-2 col-form-label">Package</label>
                    <div class="col-sm-10">
                        <select name="package_id" id="package_id" class="form-select">
                            <option value="">Select package</option>
                            @foreach ($packages as $item)
                                <option value="{{ $item->id }}" {{ ($module->package_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="url" class="col-sm-2 col-form-label">URL</label>
                    <div class="col-sm-10">
                        <input type="text" name="url" id="url" class="form-control" value="{{ $module->url }}">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="code" class="col-sm-2 col-form-label">Slug</label>
                    <div class="col-sm-10">
                        <input type="text" name="code" id="code" class="form-control" value="{{ $module->slug }}">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="icon" class="col-sm-2 col-form-label">Icon</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <label for="" class="input-group-text"><i id="m-icon" class="bi {{ $module->icon }}"></i></label>
                            <input type="text" name="icon" id="icon" class="form-control" value="{{ $module->icon }}">
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <select name="status" id="status" class="form-select">
                            <option value="0" {{ ($module->status == 0) ? 'selected' : '' }}>Disable</option>
                            <option value="1" {{ ($module->status == 1) ? 'selected' : '' }}>Enable</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="position" class="col-sm-2 col-form-label">Position</label>
                    <div class="col-sm-10">
                        <input type="text" name="position" id="position" class="form-control" value="{{ $module->position }}">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="parent" class="col-sm-2 col-form-label">Parent</label>
                    <div class="col-sm-10">
                        <input type="text" name="parent" id="parent" class="form-control" value="{{ $module->parent->id ?? '' }}">
                        <span class="form-text">{{ $module->parent->name ?? '' }}</span>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="moduleUrls" class="col-sm-2 col-form-label">Module Urls</label>
                    <div class="col-sm-10">
                        @if ($module->moduleUrls->count() > 0)
                            @foreach ($module->moduleUrls as $mod_url)
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="module_name[{{ $mod_url->id }}]" class="form-control mb-1" value="{{ $mod_url->name }}" placeholder="Name">
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <input type="text" name="module_url[{{ $mod_url->id }}]" class="form-control" value="{{ $mod_url->url }}" placeholder="URL">
                                            <div class="input-group-text">
                                                <input type="checkbox" name="delete_url[{{ $mod_url->id }}]" id="delete_url_{{ $mod_url->id }}" class="form-check-input" value="{{ $mod_url->id }}">&nbsp;<label for="delete_url_{{ $mod_url->id }}"><i class="bi bi-trash text-danger"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div id="mod-new-urls"></div>
                        <button type="button" class="btn btn-outline-info btn-sm" id="mod-new-url-btn"><i class="bi bi-plus"></i>&nbsp;Add new url</button>
                    </div>
                </div>
                <div id="mod-edit-error"></div>
                <div class="row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Update</button>
                        <button type="reset" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i>&nbsp;Reset</button>
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
@include('scripts.ajax-form-submit', ['form' => 'mod-edit'])
<script type="module">
    $(function(){
        // Icon
        $("#icon").on('keyup', function(e){
            $("#m-icon").removeClass();
            $("#m-icon").addClass('bi ' + $(this).val());
        });
        // Add new URL
        var c = 1;
        $("#mod-new-url-btn").click(function(e){
            $("#mod-new-urls").append('<div class="row" id="new_url_'+c+'"><div class="col"><input type="text" name="new_mod_name['+c+']" class="form-control mb-1" placeholder="Name"></div><div class="col"><div class="input-group"><input type="text" name="new_mod_url['+c+']" class="form-control" placeholder="URL"><button type="button" class="btn btn-danger" onclick="javascript:$(\'#new_url_'+c+'\').remove()"><i class="bi bi-trash"></i></button></div></div></div>');
            c++;
        });
    });
</script>