{{-- Category Update --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-body-secondary">
            <h3 class="modal-title">Edit Category</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
        </div>
        <div class="modal-body">
            <div id="category-success">
                <form id="category-form" action="{{ url('master/tickets/update/'.$category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="col-md-8 offset-md-2">
                            <label for="category" class="form-label fw-semibold">Enter Category Name :</label>
                            <input type="text" name="name" id="category" class="form-control" placeholder="Enter Category Name" value="{{ $category->name }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-8 offset-md-2">
                            <label for="department_id" class="form-label fw-semibold">Department :</label>
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department )
                                    <option value="{{ $department->id }}" @selected($category->department_id == $department->id )>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    <div id="category-error"></div>
                    <div class="row m-3">
                        <div class="col-md-8 offset-md-2 text-end">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit',['form' => 'category'])