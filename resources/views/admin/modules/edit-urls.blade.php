{{-- Module edit, modal view --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Module URLs - {{ $module->name }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="mod-edit-div">
            <form action="{{ url('admin/modulesUrls/' . $module->id) }}" method="POST" id="mod-url-edit-form">
                @csrf
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
                                        <input type="text" name="module_url[{{ $mod_url->id }}]" class="form-control mb-1" value="{{ $mod_url->url }}" placeholder="URL">
                                    </div>
                                </div>
                            @endforeach
                        @endif
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