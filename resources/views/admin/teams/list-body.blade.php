<div class="d-flex justify-content-between mb-1">
    <div class="row gx-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('admin/teams') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            {{-- <span class="fw-semibold">({{ $teams->total() }})</span> Records found --}}
        </div>
    </div>
    <div>
        <a href="{{ url('admin/teams/create') }}" class="btn btn-outline-success link-modal"><i class="bi bi-plus-lg"></i>&nbsp;Add Team</a>
    </div>
</div>

<div class="mt-2 table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>GEO Area</th>
                <th>Department</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($teams->count()>0)
                @foreach ($teams as $team)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $team->name }}</td>
                        <td>{{ $team->ga->name }}</td>
                        <td>{{ $team->department->name }}</td>
                        <td>{{ $team->createdBy->name ?? ''}}</td>
                        <td>{{ $team->created_at }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item link-modal" href="{{ url('admin/teams/edit/'.$team->id) }}" >Edit</a></li>
                                    <li><a class="dropdown-item link-modal" href="{{ url('admin/teams/user/create/'.$team->id) }}">Manage Users</a></li>
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