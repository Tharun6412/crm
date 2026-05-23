<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Teams - {{ $ga_name }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-hover page-sort table-striped bg-white">
                <thead class="table-success">
                    <tr>
                        <th width="1%" nowrap="nowrap">S.No</th>
                        <th>Team Name</th>
                        <th>Team Members</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($teams->count()>0)
                        @foreach ($teams as $team )
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $team->name }}</td>
                                <td>
                                    @if ($team->users->count() > 0)
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Team Members - {{ $team->users->count() }}</button>
                                            <ul class="dropdown-menu">
                                                @foreach ($team->users as $user)
                                                    <li class="dropdown-item">{{ $user->emp_id . ' - ' . $user->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $team->departments->name }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No Teams Found
                            </td>
                            </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
        </div>

    </div>
</div>