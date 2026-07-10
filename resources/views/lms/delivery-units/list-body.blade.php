<div class="d-flex justify-content-between mb-1">
    <div class="row gx-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('lms/deliveryUnits') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-1">
            <span class="fw-semibold">({{ $delivery_units->total() }})</span> Records found
        </div>
    </div>
    <div>
        <a href="{{ url('lms/deliveryUnits/create') }}" class="btn btn-outline-success link-modal"><i class="bi bi-plus-lg"></i>&nbsp;Add Delivery Unit</a>
    </div>
</div>

<div class="mt-2 table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>GA</th>
                <th>Department</th>
                <th>Delivery Manager</th>
                <th>Responsible Status</th>
                <th>Teams List</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($delivery_units->count()>0)
                @foreach ($delivery_units as $du)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a type="button" href="{{ url('lms/deliveryUnits/'.$du->id) }}" class="link-modal">{{ $du->name }}</a>
                        </td>
                        <td>{{ $du->ga->name }}</td>
                        <td>{{ $du->department?->name }}</td>
                        <td>{{ $du->duIncharge?->name }}</td>
                        <td>{{ $du->responsibleStatus?->name }}</td>
                        <td class="text-center">
                            @if($du->teams->count() > 0)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown"><i class="bi bi-people"></i> Teams {{ $du->teams->count() }}</button>
                                    <ul class="dropdown-menu">
                                        @foreach($du->teams as $team)
                                             <li>
                                                <a href="{{ url('lms/teams/show/'.$team->id) }}"
                                                class="dropdown-item link-modal">
                                                   <i class="bi bi-person"></i> {{ $team->name }} - {{ $team->users_count ?? 0 }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <span class="text-body-tertiary"><i class="bi bi-person-slash"></i></span>
                            @endif
                        </td>
                        <td>
                            @if ($du->status == 1)
                                <span>Active</span>
                            @else
                                <span>Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item link-modal" href="{{ url('lms/deliveryUnits/'.$du->id) }}"><i class="bi bi-eye-fill"></i>&nbsp;View</a></li>
                                    <li><a class="dropdown-item link-modal" href="{{ url('lms/deliveryUnits/'.$du->id.'/edit') }}" ><i class="bi bi-pencil"></i>&nbsp;Edit</a></li>
                                    <li>
                                        @if ( $du->status == 1) <a class="dropdown-item" href="javascript:statusToggle({{ $du->id }})"><i class="bi-ban"></i>&nbsp;Inactive</a>
                                        @else <a class="dropdown-item" href="javascript:statusToggle({{ $du->id }})"><i class="bi-check-lg"></i>&nbsp;Active</a>
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
    {{ $delivery_units->links('utils.paginator',['modDiv' => 'lms-du-list']) }}
</div>
@include('scripts.link-modal')
<script>
    function statusToggle(id) {
        if(confirm('Are you sure, you want to toggle the status ?.'))
        {
            $.ajax({
                url: "{{ url('lms/deliveryUnits/toggleStatus') }}/" + id,
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