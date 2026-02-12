{{-- API-KEY Edit form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">API KEY</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="api-key-edit-success">
                <form action="{{ url('admin/api-keys/' . $key->id) }}" method="POST" id="api-key-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label for="name" class="col-sm-3 col-form-label text-end">API Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{ $key->name }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="expires_at" class="col-sm-3 col-form-label text-end">Expires At</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="expires_at" id="expires_at" class="form-control" value="{{ $key->expires_at?->format('d-m-Y') }}" placeholder="DD-MM-YYY HH-MM-SS">
                                <label for="dob" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-3 col-form-label text-end">Status</label>
                        <div class="col-sm-9">
                            <input type="radio" class="btn-check" name="status" id="status_1" value="1" @checked($key->is_active == 1)>
                            <label class="btn btn-outline-success" for="status_1">Enable</label>

                            <input type="radio" class="btn-check" name="status" id="status_0" value="0" @checked($key->is_active == 0)>
                            <label class="btn btn-outline-danger" for="status_0">Disable</label>
                        </div>
                    </div>
                    <div id="api-key-edit-error" class="m-1"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-9">
                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Update</button>
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
@include('scripts.ajax-form-submit', ['form' => 'api-key-edit'])
@include('scripts.datepicker', ['list' => ['expires_at']])