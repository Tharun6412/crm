{{-- Edit complaint category form --}}

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Category</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="category-edit-success">
                <form action="{{ url('master/complaint/categories/' . $category->id) }}" method="POST" id="category-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label for="type_id" class="col-sm-2 col-form-label text-end">Type</label>
                        <div class="col-sm-9">
                            <select name="type_id" id="type_id" class="form-select">
                                <option value="">Select Type</option>
                                @foreach ($types as $item)
                                    <option value="{{ $item->id }}" @selected($category->type_id == $item->id)>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="department_id" class="col-sm-2 col-form-label text-end">Department</label>
                        <div class="col-sm-9">
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Departmentg</option>
                                @foreach ($departments as $item)
                                    <option value="{{ $item->id }}" @selected($category->department_id == $item->id)>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="name" class="col-sm-2 col-form-label text-end">Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="resolution" class="col-sm-2 col-form-label text-end">Resolution</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="resolution" id="resolution" class="form-control text-end" value="{{ $category->resolution }}">
                                <select name="resolution_type" id="resolution_type" class="form-select">
                                    <option value="1" @selected($category->resolution_type == 1)>Days</option>
                                    <option value="2" @selected($category->resolution_type == 2)>Hours</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="parent_id" class="col-sm-2 col-form-label text-end">Parent</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="parent_id" id="parent_id" class="form-control text-end" value="{{ $category->parent_id }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="status" class="col-sm-2 col-form-label text-end">Status</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <select name="status" id="status" class="form-select">
                                    <option value="1" @selected($category->status == 1)>Enable</option>
                                    <option value="0" @selected($category->status == 0)>Disable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="position" class="col-sm-2 col-form-label text-end">Position</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" name="position" id="position" class="form-control text-end" value="{{ $category->position }}">
                            </div>
                        </div>
                    </div>
                    <div id="category-edit-error" class="text-danger"></div>
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
@include('scripts.ajax-form-submit', ['form' => 'category-edit'])