{{-- Team Create Modal --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5">Edit Delivery Unit</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" method="POST" action="{{ url('lms/deliveryUnits/'.$delivery_unit->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        {{-- Team Name --}}
                        <label for="name" class="col-sm-2 col-form-label text-end">Name :</label>
                        <div class="col-sm-4">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Delivery Unit Name" value="{{ $delivery_unit->name }}">
                        </div>
                        {{-- Department --}}
                        <label for="department_id" class="col-sm-2 col-form-label text-end">Department :</label>
                        <div class="col-sm-4">
                            <span>{{ $delivery_unit->department?->name }}</span>
                        </div>
                    </div>
                    {{-- Geo Area --}}
                    <div class="row mb-3">
                        <label for="ga_id" class="col-sm-2 col-form-label text-end">Geo Area :</label>
                        <div class="col-sm-4">
                            <span>{{ $delivery_unit->ga?->name }}</span>
                        </div>                
                        <label for="du_incharge_id" class="col-sm-2 col-form-label text-end">Delivery Manager :</label>
                        <div class="col-sm-4">
                            <select name="delivery_manager_id" id="delivery_manager_id" class="form-select">
                                <option value="">Select</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected($user->id == $delivery_unit?->manager_id)>
                                        {{ $user?->name }} - {{ $user?->emp_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    {{-- Charge Areas --}}
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <h4>Charge Areas and Areas :</h4>
                        </div>
                        <div class="col-sm-12">
                            <div id="area_id" class="border rounded p-3">
                                @foreach($charge_areas as $ca)
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                            {{ $ca->name }}
                                        </h6>
                                        <div class="row">
                                            @foreach($areas->where('ca_id', $ca->id) as $area)
                                                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="area_id[]"
                                                            value="{{ $area->id }}"
                                                            id="area_{{ $area->id }}"
                                                            @checked(in_array($area->id, $selected_areas))
                                                            @disabled(in_array($area->id, $disabled_areas))
                                                        >
                                                        <label class="form-check-label" for="area_{{ $area->id }}">
                                                            {{ $area->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
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
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Update</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit',['form' => 'team'])
<script type="text/javascript">
    // Get Areas List
    function getAreasByCa(ca_id)
    {
        $.get("{{ url('lms/deliveryUnits/getCaAreas') }}",{'ca_id': ca_id}, function(response){
            //for charge areas
            let options = '';
            if(response.areas && response.areas.length > 0) {
                response.areas.forEach(function(area) {
                options += `<div class="form-check" style="display:inline-block; width:220px; margin-bottom:10px;">
                <input class="form-check-input" type="checkbox" name="area_id[]" value="${area.id}" id="area_${area.id}">
                <label class="form-check-label" for="area_${area.id}">${area.name}</label>
                </div>`;
                });
            } else {
                options = `<span class="text-danger">No Areas Found</span>`;
            }
            $('#area_id').html(options); 
        });
    }
    $(function(){
        $("#ga_id").on('change', function(e) {
            $.get("{{ url('lms/teams/editGaCas') }}",{'ga_id': e.target.value},function(response){
                $('#edit_charge_area').html(response);
            });
        });
    });
</script>
