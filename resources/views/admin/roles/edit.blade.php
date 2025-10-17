{{-- Role edit model --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Role - {{ $role->name }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @if (in_array($role->id, [1, 2]))
                <div class="alert alert-success mb-0">Full access!</div>
            @else
                <div id="role-edit-success">
                    <form action="{{ url('admin/roles/' . $role->id) }}" id="role-edit-form" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $role->id }}">
                        <div class="row mb-1">
                            <label for="name" class="col-sm-3 col-form-label text-end">Role name</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" is-invalid>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label class="col-sm-3 col-form-label text-end">Status</label>
                            <div class="col-sm-9 pt-2">
                                <input type="radio" name="status" id="status_1" class="form-check-input" value="1" @checked($role->status == 1)>
                                <label for="status_1" class="form-check-label">Enable</label>
                                <input type="radio" name="status" id="status_0" class="form-check-input" value="0" @checked($role->status == 0)>
                                <label for="status_0" class="form-check-label">Disable</label>
                            </div>
                        </div>
                        <div>
                            <h4>Modules</h4>
                            @php
                                // Create rights / module actions array from relational fn
                                $rights = $role->roleActions->pluck('module_action_id')->toArray();
                            @endphp
                            <ul class="tree">
                                @include('admin.roles.role-module-item', ['child_modules' => $modules])
                            </ul>
                        </div>
                        <div id="role-edit-error"></div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Update</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('admin.modules.module-tree-script')
@include('scripts.ajax-form-submit', ['form' => 'role-edit'])