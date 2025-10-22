{{-- User details show view --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User - {{ $user->emp_id }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row mb-2">
                <label for="" class="col-sm-2 text-end">Emp ID:</label>
                <div class="col-sm-4">{{ $user->emp_id }}</div>
                <label for="" class="col-sm-2 text-end">Email:</label>
                <div class="col-sm-4">{{ $user->email }}</div>
            </div>
            <div class="row mb-2">
                {{-- <label for="" class="col-sm-2 text-end">Phone:</label>
                <div class="col-sm-4">{{ $user->mobile }}</div> --}}
                {{-- <label for="" class="col-sm-2 text-end">DOB:</label>
                <div class="col-sm-4">{{ (!empty($user->dob)) ? $user->dob->format('d.m.Y') : '-' }}</div> --}}
            </div>
            <div id="user-edit-success">
            <form action="{{ url('admin/users/' . $user->id) }}" method="POST" id="user-edit-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $user->id }}">
                <div class="row mb-2">
                    <label for="first_name" class="col-sm-2 col-form-label text-end">First name</label>
                    <div class="col-sm-4">
                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $user->first_name }}">
                    </div>
                    <label for="last_name" class="col-sm-2 col-form-label text-end">Last name</label>
                    <div class="col-sm-4">
                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $user->last_name }}">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="mobile" class="col-sm-2 col-form-label text-end">Phone</label>
                    <div class="col-sm-4">
                        <input type="text" name="mobile" id="mobile" class="form-control" value="{{ $user->mobile }}">
                    </div>
                    <label for="dob" class="col-sm-2 col-form-label text-end">DOB</label>
                    <div class="col-sm-4">
                        <input type="text" name="dob" id="dob" class="form-control" value="{{ (!empty($user->dob)) ? $user->dob->format('d-m-Y') : '' }}" placeholder="DD-MM-YYY">
                    </div>
                </div>
                {{-- <div class="row mb-2">
                    <label for="ga_id" class="col-sm-2 col-form-label text-end">Geo Area</label>
                    <div class="col-sm-4">
                        <select name="ga_id" id="ga_id" class="form-select">
                            <option value="">Select GA</option>
                            @foreach ($geo_areas as $item)
                                <option value="{{ $item->id }}" @selected($user->ga_id == $item->id)>{{ $item->code . '-' . $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="role_id" class="col-sm-2 col-form-label text-end">Role</label>
                    <div class="col-sm-4">
                        <select name="role_id" id="role_id" class="form-select">
                            <option value="">Select role</option>
                            @foreach ($roles as $item)
                                <option value="{{ $item->id}}" @selected($user->role_id == $item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="row mb-3">
                    <label for="department_id" class="col-sm-2 col-form-label text-end">Department</label>
                    <div class="col-sm-4">
                        <select name="department_id" id="department_id" class="form-select">
                            <option value="">Select department</option>
                            @foreach ($departments as $item)
                                <option value="{{ $item->id }}" @selected($user->department_id == $item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="status" class="col-sm-2 col-form-label text-end">Status</label>
                    <div class="col-sm-4">
                        <select name="status" id="status" class="form-select border-{{ ($user->status == 1) ? 'success' : 'danger'}}">
                            <option value="1" @selected($user->status == 1)>Active</option>
                            <option value="0" @selected($user->status == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label text-end">Cluster restriction</label>
                    <div class="col-sm-4 mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="cluster_restriction" id="cluster_restrict1" value="1" @checked($user->cluster_restriction == 1)>
                            <label for="cluster_restrict1" class="form-check-label">True</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="cluster_restriction" id="cluster_restrict0" value="0" @checked($user->cluster_restriction == 0)>
                            <label for="cluster_restrict0" class="form-check-label">False</label>
                        </div>
                    </div>
                    <label class="col-sm-2 col-form-label text-end">GA restriction</label>
                    <div class="col-sm-4 mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ga_restriction" id="ga_restrict1" value="1" @checked($user->ga_restriction == 1)>
                            <label for="ga_restrict1" class="form-check-label">True</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ga_restriction" id="ga_restrict0" value="0" @checked($user->ga_restriction == 0)>
                            <label for="ga_restrict0" class="form-check-label">False</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 text-end">
                        <h5>Roles</h5>
                    </div>
                    <div class="col-sm-10">
                        <div class="row row-cols-3">
                            @php
                                $user_roles = $user->roles->pluck('id')->toArray();
                            @endphp
                            @foreach ($roles as $role)
                                <div class="col">
                                    <div class="form-check">
                                        <input type="checkbox" name="roles[{{ $role->id }}]" id="edit_role_{{ $role->id }}" class="form-check-input" value="{{ $role->id }}" @checked(in_array($role->id, $user_roles))>
                                        <label for="edit_role_{{ $role->id }}" class="form-check-label">{{ $role->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
                {{-- Geo Areas --}}
                <div class="row">
                    <div class="col-sm-2 text-end">
                        <h5>Geo Areas</h5>
                    </div>
                    <div class="col-sm-10">
                        <div class="row row-cols-3">
                            @php
                                $user_gas = $user->ga->pluck('id')->toArray();
                            @endphp
                            @foreach ($geo_areas as $ga)
                                <div class="col">
                                    <div class="form-check">
                                        <input type="checkbox" name="geo_areas[{{ $ga->id }}]" id="edit_ga_{{ $ga->id }}" class="form-check-input" value="{{ $ga->id }}" @checked(in_array($ga->id, $user_gas))>
                                        <label for="edit_ga_{{ $ga->id }}" class="form-check-label">{{ $ga->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mb-3" id="user-edit-error"></div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Update details</button>
                </div>
            </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'user-edit'])
@include('scripts.datepicker', ['list' => ['dob']])