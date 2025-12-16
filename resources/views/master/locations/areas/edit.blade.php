{{-- Edit area form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Area - {{ $area->ca->district->name ?? '' }} -> {{ $area->ca->name }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="loc-area-edit-success">
                <form action="{{ url('master/location/areas/' . $area->id) }}" method="POST" id="loc-area-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label for="name" class="col-sm-2 col-form-label text-end">Area Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{ $area->name }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="status" class="col-sm-2 col-form-label text-end">Status</label>
                        <div class="col-sm-9">
                            <select name="status" id="status" class="form-select">
                                <option value="1" @selected($area->status == 1)>Enable</option>
                                <option value="0" @selected($area->status == 0)>Disable</option>
                            </select>
                        </div>
                    </div>
                    <div id="loc-area-edit-error" class="text-danger"></div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-9">
                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Save</button>
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
@include('scripts.ajax-form-submit', ['form' => 'loc-area-edit'])
