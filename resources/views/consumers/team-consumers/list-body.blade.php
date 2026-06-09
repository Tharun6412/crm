<div class="d-flex justify-content-between mb-1">
    <div class="row gx-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
<<<<<<< Updated upstream
            <a href="{{ url('consumers/waiting/pending-consumers') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
=======
            <a href="{{ url('lms/leads') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
>>>>>>> Stashed changes
        </div>
        <div class="col-auto">
            <span class="fw-semibold">({{ $consumers->total() }})</span> Records found
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>S.No</th>
                <th>CRN</th>
                <th>Consumer Name</th>
                <th>Consumer Status</th>
                <th>Assigned To</th>
                <th>Status
                    @php
                        $status_filters = ['' =>'Not Assigned', 0 => 'Assigned', 1 => 'Completed'];
                    @endphp
                    <x-admin.status-filter name="status" :data='$status_filters' class="float-end"/>
                </th>
                <th>Assign By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($consumers->count()>0)
                @foreach ($consumers as $consumer )
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $consumer->crn }}</td>
                        <td>{{ $consumer->name }}</td>
                        <td>{{ $consumer->status->name }}</td>
                        <td>{{ $consumer->teamConsumer->first()?->team?->name ?? '' }}</td>
                        <td>
                            @if($consumer->teamConsumer->first()?->status == '')
                                Not Assigned
                            @elseif ($consumer->teamConsumer->first()?->status == 0)
                                Assigned
                            @elseif ($consumer->teamConsumer->first()?->status == 1)
                                Completed
                            @endif
                        </td>
                        <td>{{ $consumer->teamConsumer->first()?->updatedBy?->name ?? '' }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    @if($consumer->teamConsumer->first()?->status == '')
<<<<<<< Updated upstream
                                        <li><a class="dropdown-item link-modal" href="{{ url('consumers/waiting/pending-consumers/create/'.$consumer->id) }}"><i class="bi bi-person-check-fill"></i>&nbsp;Assign</a></li>
=======
                                        <li><a class="dropdown-item link-modal" href="{{ url('consumers/waiting/pending-consumers/'.$consumer->id.'/edit') }}"><i class="bi bi-person-check-fill"></i>&nbsp;Assign</a></li>
>>>>>>> Stashed changes
                                    @endif
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
<div>{{ $consumers->links('utils.paginator',['modDiv' => 'team-consumers-list'])}}</div>
@include('scripts.link-modal')