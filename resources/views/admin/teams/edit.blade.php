{{-- Team Edit Modal --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5">Edit Team - {{ $team->name }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"> </button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" method="POST" action="{{ url('admin/teams/update/' . $team->id) }}">
                    @csrf
                    @method('PUT')
                    {{-- Team Name --}}
                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label text-end">Team Name :</label>
                        <div class="col-sm-4">
                            <input type="text" name="name" id="name" class="form-control" value="{{ $team->name }}" placeholder="Enter Team Name">
                        </div>
                        {{-- Department --}}
                        <label for="department_id" class="col-sm-2 col-form-label text-end">Department :</label>
                        <div class="col-sm-4">
                            <select name="department_id" id="department_id" class="form-select">
                                <option value=""> Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"@selected($team->department_id == $department->id)>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Geo Area --}}
                    <div class="row mb-3">
                        <label for="ga_id" class="col-sm-2 col-form-label text-end">Geo Area :</label>
                        <div class="col-sm-4">
                            <select name="ga_id" id="ga_id" class="form-select">
                                <option value="">Select Geo Area</option>
                                @foreach ($geo_areas as $ga)
                                    <option value="{{ $ga->id }}"@selected($team->ga_id == $ga->id)>{{ $ga->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    {{-- Charge Areas --}}
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <h4>Charge Areas :</h4>
                        </div>
                        <div class="col-sm-12">
                            <div id="ca_id" class="row row-cols-4 border rounded p-3">
                                @php
                                    $teamCas = $team->cas->pluck('id')->toArray();
                                @endphp
                                @foreach ($cas as $ca)
                                    <div class="col mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="ca_id[]" value="{{ $ca->id }}" id="ca_{{ $ca->id }}"@checked(in_array($ca->id, $teamCas))>
                                            <label class="form-check-label" for="ca_{{ $ca->id }}">{{ $ca->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
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
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>

@include('scripts.ajax-form-submit', ['form' => 'team'])
<script type="module">
    $(function(){
        $("#ga_id").on('change', function(e) {
            $.get("{{ url('teams/gaCas') }}",{'ga_id': e.target.value},function(response){
                let options = '';
                if(response.cas && response.cas.length > 0) {
                    response.cas.forEach(function(ca) {
                    options += `<div class="form-check" style="display:inline-block; width:220px; margin-bottom:10px;">
                    <input class="form-check-input" type="checkbox" name="ca_id[]" value="${ca.id}" id="ca_${ca.id}">
                    <label class="form-check-label" for="ca_${ca.id}">${ca.name}</label>
                    </div>`;
                    });

                } else {

                    options = `<span class="text-danger">No Charge Areas Found</span>`;
                }

                $('#ca_id').html(options);
            });
        });
    });
</script>
