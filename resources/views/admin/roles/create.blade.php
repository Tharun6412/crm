{{-- Role create model --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Role</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="role-add-success">
                <form action="{{ url('admin/roles/') }}" id="role-add-form" method="POST">
                    @csrf
                    <div class="row mb-1">
                        <label for="name" class="col-sm-3 col-form-label text-end">Role name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" class="form-control" is-invalid>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-sm-3 col-form-label text-end">Status</label>
                        <div class="col-sm-9 pt-2">
                            <input type="radio" name="status" id="status_1" class="form-check-input" value="1">
                            <label for="status_1" class="form-check-label">Enable</label>
                            <input type="radio" name="status" id="status_0" class="form-check-input" value="0">
                            <label for="status_0" class="form-check-label">Disable</label>
                        </div>
                    </div>
                    <div id="role-add-error"></div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp;Add role</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'role-add'])