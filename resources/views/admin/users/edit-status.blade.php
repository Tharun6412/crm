{{-- User details show view --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User Status - {{ $user->emp_id }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="user-status-edit-success">
                <form action="{{ url('admin/users/status/' . $user->id) }}" method="POST" id="user-status-edit-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="row mb-2">
                        <label for="status" class="col-sm-3 col-form-label text-end">Status</label>
                        <div class="col-sm-8">
                            <select name="status" id="status" class="form-select border-{{ ($user->status_id == 1) ? 'success' : 'danger'}}">
                                <option value="1" @selected($user->status_id == 1)>Active</option>
                                <option value="2" @selected($user->status_id == 2)>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="status" class="col-sm-3 col-form-label text-end">Notes:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="notes" id="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="mb-3" id="user-status-edit-error"></div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Update status</button>
                    </div>
                </form>
                @include('admin.users.status-history')
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'user-status-edit'])