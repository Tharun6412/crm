{{-- Edit Subareas --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit SubAreas</h1>
            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="close"></button>
        </div>
        <div class="modal-body">
            <div id="subarea-edit-success">
                <form id="subarea-edit-form" action="{{ url('master/location/subareas/'.$subarea->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label for="name" class="col-sm-3 col-form-label text-end">SubArea : </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{ $subarea->name }}">
                        </div>
                    </div>
                    <div id="subarea-edit-error" class="text-danger"></div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-9">
                            <button type="submit" class="btn btn-success"><i class="bi bi-plus-lg"></i>&nbsp;Update</button>
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
{{-- Scripts --}}
@include('scripts.ajax-form-submit', ['form' => 'subarea-edit'])