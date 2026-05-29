<div class="d-flex justify-content-end">
    <a href="{{ url('master/tickets/create') }}" class="btn btn-outline-success link-modal"><i class="bi bi-plus-lg"></i>&nbsp;Create</a>
</div>
<div class="table-responsive mt-2">
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>S.No</th>
                <th>Category</th>
                <th>Department</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($categories->count()>0)
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->departments->name }}</td>
                        <td>{{ $category->createdBy->name }}</td>
                        <td>{{ $category->updatedBy->name ?? ''}}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item link-modal" href="{{ url('master/tickets/edit/'.$category->id) }}"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10">No Records Found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@include('scripts.link-modal')