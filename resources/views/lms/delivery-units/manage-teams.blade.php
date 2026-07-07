{{-- Based on the team assign users page --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Manage Teams - {{ $delivery_unit->name }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="du-team-success">
                <div>
                    <x-lms.du-details :du="$delivery_unit" class="bg-info-subtle" />
                </div>
                <form id="du-team-form" method="POST" action="{{ url('lms/deliveryUnits/updateDuTeams/'.$delivery_unit->id) }}">
                    @csrf
                    @php
                        $du_teams = $delivery_unit->teams->pluck('id')->toArray();
                    @endphp
                    {{-- Users --}}
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <h4>Teams :</h4>
                        </div>
                        <div class="col-sm-12">
                            <div class="row row-cols-3 border rounded p-3">
                                @if($teams->count() > 0)
                                    @foreach ($teams as $team)
                                        <div class="col mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="team_id[]" value="{{ $team->id }}" id="team_{{ $team->id }}"@checked(in_array($team->id, $du_teams))>
                                                <label class="form-check-label" for="team_{{ $team->id }}">{{ $team->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-danger mb-0">
                                            No Teams Found
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Error --}}
                    <div class="mt-3" id="du-team-error"></div>
                        @if ($teams->count() > 0)
                            {{-- Submit --}}
                            <div class="text-center mt-4"><button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Save Users</button></div>
                        @endif
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'du-team'])