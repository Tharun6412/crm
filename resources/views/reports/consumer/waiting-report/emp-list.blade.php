{{-- Employee collection show details --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Consumer Waiting Report&nbsp;-&nbsp;{{ $status_name ?? "ALL" }}&nbsp;-&nbsp;{{ $ga_name ?? "ALL"}}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer sattus --}}
            @if ($users_list->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Teams</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Consumers Waiting</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users_list as $user)
                            @php
                                $teamCount = $user->teams->filter(function ($team) use ($user) {
                                    // Team department must match
                                    if ($team->department_id != $user->department_id) {
                                        return false;
                                    }
                                    // User CA and Team CA must intersect
                                    return $team->cas->pluck('id')
                                        ->intersect($user->ca->pluck('id'))
                                        ->isNotEmpty();

                                })->count();
                            @endphp
                            <tr>
                                <td>{{ $teamCount ?? 0 }}</td>
                                <td>{{ $user?->name }}</td>
                                <td>{{ $user->department?->name }}</td>
                                <td>{{ $user->count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    No employees available for the selected Status.
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.export-table', [
    'table' => 'emp-clcn-dtls',
    'button' => 'exportClnBtn',
    'tabBased' => false,
    'filename' => 'employee-collection-details',
    'sheet'    => 'Report',
])