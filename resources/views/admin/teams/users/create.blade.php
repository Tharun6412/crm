<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Users - {{ $team->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-user-success">
            <form id="team-user-form" method="POST" action="{{ url('admin/teams/user/store/'.$team->id) }}">
                @csrf
                @php
                    $teamUsers = $team->users->pluck('id')->toArray();
                @endphp
                {{-- Users --}}
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <h6>Users based on Team Ga</h6>
                    </div>
                    <div class="col-sm-12">
                        <div class="row row-cols-3 border rounded p-3">
                            @forelse ($users as $user)
                                <div class="col mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="user_id[]" value="{{ $user->id }}" id="user_{{ $user->id }}"@checked(in_array($user->id, $teamUsers))>
                                        <label class="form-check-label" for="user_{{ $user->id }}">{{ $user->first_name }}{{ $user->last_name }}</label>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center">
                                    <span class="text-danger">No Users Found</span>
                                </div>
                            @endforelse
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