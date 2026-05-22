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
            <span class="fw-semibold">({{ $teams->total() }})</span> Records found
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
                <th>Team Name</th>
                <th>GA <x-master.ga-filter class="float-end"/></th>
                <th>Charge Areas</th>
                <th>Department <x-master.department-filter class="float-end"/></th>
                <th>Status</th>
                <th>Employees</th>
                <th nowrap>Created At</th>
                
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($teams->count()>0)
                @foreach ($teams as $team)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a class="link-modal" href="{{ url('admin/teams/show/'.$team->id) }}">{{ $team->name }}</a></td>
                        <td>{{ $team->ga->name }}</td>
                        <td>
                            @if ($team->cas->count() > 0)
                                @foreach ($team->cas as $ca)
                                    @if ($loop->iteration == 1)
                                        <div class="btn-group w-100">
                                            <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ $ca->name }}
                                            </button>
                                            <ul class="dropdown-menu">                
                                    @else
                                        <li class="dropdown-item"><i class="bi bi-ca-alt-fill"></i>&nbsp;{{ $ca->name }}</li>
                                    @endif
                                @endforeach
                                    </ul>
                                </div>
                            @endif
                        </td>
                        <td>{{ $team->departments->name }}</td>
                        <td>
                            @if($team->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td> 
                        <td>{{ $team->users_count }}</td>                       
                        <td>{{ dateFormat($team->created_at) }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item link-modal" href="{{ url('admin/teams/show/'.$team->id) }}"><i class="bi bi-eye-fill"></i>&nbsp;View</a></li>
                                    <li><a class="dropdown-item link-modal" href="{{ url('admin/teams/edit/'.$team->id) }}" ><i class="bi bi-pencil"></i>&nbsp;Edit</a></li>
                                    <li><a class="dropdown-item link-modal" href="{{ url('admin/teams/user/create/'.$team->id) }}"><i class="bi-people"></i>&nbsp;Manage Users</a></li>
                                    <li>
                                        @if ( $team->status == 1) <a class="dropdown-item" href="javascript:statusToggle({{ $team->id }})"><i class="bi-ban"></i>&nbsp;Inactive</a>
                                        @else <a class="dropdown-item" href="javascript:statusToggle({{ $team->id }})"><i class="bi-check-lg"></i>&nbsp;Active</a>
                                        @endif
                                    </li>
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
<div>
    {{ $teams->links('utils.paginator',['modDiv' => 'teams-list']) }}
</div>
@include('scripts.link-modal')
<script>
    function statusToggle(id) {
        if(confirm('Are you sure, you want to toggle the status ?.'))
        {
            $.ajax({
                url: "{{ url('admin/teams') }}/" + id + "/toggleStatus",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(error) {
                    alert('Something went wrong.');
                }
            });
        }
    }
</script>