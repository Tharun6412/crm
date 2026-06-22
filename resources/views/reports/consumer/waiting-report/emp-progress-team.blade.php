{{-- Employee collection show details --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-secondary-subtle">
            <h2 class="modal-title fs-5" id="exampleModalLabel">{{ $team?->name }}&nbsp;-&nbsp;{{ $team->departments?->name }}</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @if ($team->users->count() > 0)
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <div class="text-start"><strong>({{ $team->users->count() }} records)</strong></div>
                        <div class="text-end">Team Coordinator&nbsp;-&nbsp;<strong>{{ $team->responsibleUser->name ?? '' }}</strong></div>
                    </div>
                </div>
                <table class="table table-bordered table-hover align-middle" id="team-progress-table">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Employee</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($team->users as $emp)
                            @php
                                $user = $employees[$emp->id]['total'] ?? 0;
                                $count += $user;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $emp->name ?? '' }}&nbsp;({{ $emp->emp_id ?? '' }})</td>
                                <td>
                                    {{ $user }}
                                </td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="2" class="text-end">Total</td>
                                <td>{{ $count }}</td>
                            </tr>
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">
                    <span>No employees found</span>
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <!-- Export -->
            @if ($team->name)
                <button type="button" id="exportBtn" class="btn btn-outline-info"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
            @endif
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.export-table', [
    'table' => 'team-progress-table',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'team_progress'." - ". $team?->name,
    'sheet'    => 'Report',
])