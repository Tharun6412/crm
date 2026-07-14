{{-- Team Edit Modal --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-secondary-subtle">
            <h4 class="modal-title fw-semibold"><i class="bi bi-people-fill"></i>&nbsp;Edit Team - <span class="fw-bold text-primary">{{ $team->name }}</span></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"> </button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" method="POST" action="{{ url('lms/teams/update/' . $team->id) }}">
                    @csrf
                    @method('PUT')
                    {{-- Team Name --}}
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label for="name" class="form-label">Team Name :</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ $team->name }}" placeholder="Enter Team Name">
                        </div>
                        <div class="col-sm-4 pt-2">
                            <label class="form-label">Department :</label><br/>
                            <strong>{{ $team->departments->name }}</strong>
                        </div>
                        <div class="col-sm-4 pt-2">
                            <label class="form-label">Geo Area :</label><br/>
                            <strong>{{ $team->ga->name }}</strong>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label for="responsible_user_id" class="form-label">Team Lead : </label>
                            <select name="responsible_user_id" id="responsible_user_id" class="form-select">
                                <option value="">Select</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"@selected($user->id == $team->responsible_user_id)>{{ $user->emp_id }} - {{ $user->name }} - {{ $user->department?->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Delivery Unit :</label><br/>
                            <strong>{{ $team->deliveryUnit?->name }}</strong>
                        </div>
                    </div>
                    {{-- Charge Areas --}}
                    <div class="card border shadow-sm mt-2">
                        <div class="card-header bg-info-subtle fw-semibold"><i class="bi bi-pin-map-fill text-secondary"></i>&nbsp;Charge Areas and Areas List</div>
                        <div class="p-2">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div id="area_id" class="p-3">
                                @php
                                    $teamAreas = $team->areas->pluck('id')->toArray();
                                @endphp
                                @foreach ($cas as $ca)
                                    <div class="mb-3">
                                        <h4 class="fw-bold text-primary border-bottom pb-2">{{ $ca->name }}
                                            <input type="checkbox" name="ca_all" onchange="getSelectAllAreasUpdate(this, {{ $ca->id }})"/>
                                        </h4>
                                        <div class="row">
                                            @foreach ($areas->where('ca_id', $ca->id) as $area)
                                                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input border-1 border-primary edit_area_{{ $ca->id }}" type="checkbox" name="area_id[]" value="{{ $area->id }}" id="area_{{ $area->id }}" @checked(in_array($area->id, $teamAreas)) @disabled(in_array($area->id, $disabled_areas))>
                                                        <label class="form-check-label" for="area_{{ $area->id }}">{{ $area->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Error --}}
                    <div class="mb-3" id="team-error"></div>
                    {{-- Submit --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Update Team</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Footer --}}
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btm-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    // Get Select All Areas By CA selection
    function getSelectAllAreasUpdate(caCheckbox, caId)
    {
        let checked = caCheckbox.checked;
        document.querySelectorAll('.edit_area_' + caId).forEach(function(area) {
            if (!area.disabled) {
                area.checked = checked;
            }
        });
    }
</script>
@include('scripts.ajax-form-submit', ['form' => 'team'])