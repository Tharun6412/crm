{{-- Create New User form --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create New User</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="user-add-success">
                <form action="{{ url('admin/users') }}" method="POST" id="user-add-form">
                    @csrf
                    <div class="row mb-2">
                        <label for="emp_id" class="col-sm-2 text-end">Emp ID</label>
                        <div class="col-sm-4">
                            <input type="text" name="emp_id" id="emp_id" class="form-control">
                        </div>
                        <label for="email" class="col-sm-2 text-end">Email</label>
                        <div class="col-sm-4">
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="first_name" class="col-sm-2 col-form-label text-end">First name</label>
                        <div class="col-sm-4">
                            <input type="text" name="first_name" id="first_name" class="form-control">
                        </div>
                        <label for="last_name" class="col-sm-2 col-form-label text-end">Last name</label>
                        <div class="col-sm-4">
                            <input type="text" name="last_name" id="last_name" class="form-control">
                        </div>
                    </div>
                <div class="row mb-2">
                    <label for="mobile" class="col-sm-2 col-form-label text-end">Phone</label>
                    <div class="col-sm-4">
                        <input type="text" name="mobile" id="mobile" class="form-control">
                    </div>
                    <label for="dob" class="col-sm-2 col-form-label text-end">DOB</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" name="dob" id="dob" class="form-control" placeholder="DD-MM-YYY">
                            <label for="dob" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="department_id" class="col-sm-2 col-form-label text-end">Department</label>
                    <div class="col-sm-4">
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="">Select department</option>
                            @foreach ($departments as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="doj" class="col-sm-2 col-form-label text-end">DOJ</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" name="doj" id="doj" class="form-control" placeholder="DD-MM-YYY">
                            <label for="doj" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="type_id" class="col-sm-2 col-form-label text-end">Employee Type</label>
                    <div class="col-sm-4">
                        <select name="type_id" id="type_id" class="form-select">
                            <option value="">Select Type</option>
                            @foreach ($types as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3 d-none">
                    <label class="col-sm-2 col-form-label text-end">Cluster restriction</label>
                    <div class="col-sm-4 mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="cluster_restriction" id="cluster_restrict1" value="1">
                            <label for="cluster_restrict1" class="form-check-label">True</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="cluster_restriction" id="cluster_restrict0" value="0">
                            <label for="cluster_restrict0" class="form-check-label">False</label>
                        </div>
                    </div>
                    <label class="col-sm-2 col-form-label text-end">GA restriction</label>
                    <div class="col-sm-4 mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ga_restriction" id="ga_restrict1" value="1">
                            <label for="ga_restrict1" class="form-check-label">True</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ga_restriction" id="ga_restrict0" value="0">
                            <label for="ga_restrict0" class="form-check-label">False</label>
                        </div>
                    </div>
                </div>
                {{-- Geo Areas --}}
                <div class="row">
                    <div class="col-sm-12 text-start">
                        <h4>Geo Areas</h4>
                    </div>
                    <div class="col-sm-12">
                        <div class="row row-cols-5">
                            @foreach ($geo_areas as $ga)
                                <div class="col">
                                    <div class="form-check">
                                        <input type="checkbox" name="geo_areas[{{ $ga->id }}]" id="add_ga_{{ $ga->id }}" class="form-check-input border-1 border-dark" value="{{ $ga->id }}">
                                        <label for="add_ga_{{ $ga->id }}" class="form-check-label">{{ $ga->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-start">
                        <h5>Roles</h5>
                    </div>
                    <div class="col-sm-12">
                        <div class="row row-cols-5">
                            @foreach ($roles as $role)
                                <div class="col">
                                    <div class="form-check">
                                        <input type="checkbox" name="roles[{{ $role->id }}]" id="add_role_{{ $role->id }}" class="form-check-input" value="{{ $role->id }}">
                                        <label for="add_role_{{ $role->id }}" class="form-check-label">{{ $role->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mb-3" id="user-add-error"></div>
                <div class="text-start">
                    <button type="submit" class="btn btn-success"><i class="bi bi-person-plus"></i>&nbsp;Create User</button>
                </div>
            </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'user-add'])
@include('scripts.datepicker', ['list' => ['dob', 'doj']])