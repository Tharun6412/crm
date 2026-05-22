{{-- Based on the team assign users page --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Manage Employees - {{ $team->name }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-user-success">
                <div>
                    <x-admin.teams-details :teams="$team" class="bg-info-subtle" />
                </div>
                <form id="team-user-form" method="POST" action="{{ url('admin/teams/user/store/'.$team->id) }}">
                    @csrf
                    @php
                        $teamUsers = $team->users->pluck('id')->toArray();
                    @endphp
                    {{-- Users --}}
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <h4>Employees :</h4>
                        </div>
                        <div class="col-sm-12">
                            <div class="row row-cols-3 border rounded p-3">
                                @if($users->count() > 0)
                                    @foreach ($users as $user)
                                        <div class="col mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="user_id[]" value="{{ $user->id }}" id="user_{{ $user->id }}"@checked(in_array($user->id, $teamUsers))>
                                                <label class="form-check-label" for="user_{{ $user->id }}">{{ $user->emp_id }} - {{ $user->first_name }}{{ $user->last_name }} - {{ $user->department->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12 text-center">
                                        <span class="text-danger">No Users Found</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Error --}}
                    <div class="mt-3" id="team-user-error"></div>
                    {{-- Submit --}}
                    <div class="text-center mt-4"><button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Save Users</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-file-submit', ['form' => 'team-user'])