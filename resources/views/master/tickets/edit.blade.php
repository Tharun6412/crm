{{-- Category Update --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1>Edit Category</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
        </div>
        <div class="modal-body">
            <div id="category-success">
                <form id="category-form" action="{{ url('master/tickets/update/'.$category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <label for="category" class="form-label">Enter Category Name :</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="category" class="form-control" placeholder="Enter Category Name" value="{{ $category->name }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="department_id" class="form-label">Department :</label>
                        <div class="col-sm-9">
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department )
                                    <option value="{{ $department->id }}" @selected($category->department_id == $department->id )>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    <div id="category-error"></div>
                    <div class="row">
                        <div class="col-sm-9 text-end">
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