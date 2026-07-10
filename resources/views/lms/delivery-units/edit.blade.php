{{-- Team Create Modal --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-secondary-subtle">
            <h4 class="modal-title fw-semibold">Edit Delivery Unit</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" method="POST" action="{{ url('lms/deliveryUnits/'.$delivery_unit->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        {{-- Team Name --}}
                        <div class="col-sm-4">
                            <label for="name" class="form-label">Name :</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Delivery Unit Name" value="{{ $delivery_unit->name }}">
                        </div>
                        {{-- Department --}}
                        <div class="col-sm-4">
                            <label for="department_id" class="form-label">Department :</label><br/>
                            <strong>{{ $delivery_unit->department?->name }}</strong>
                        </div>
                        <div class="col-sm-4">
                            <label for="ga_id" class="form-label">Geo Area :</label><br/>
                            <strong>{{ $delivery_unit->ga?->name }}</strong>
                        </div>                
                        <div class="col-sm-4 mt-2">
                            <label for="du_incharge_id" class="form-label">Delivery Manager :</label>
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
                    <div class="card border shadow-sm mt-2">
                        <div class="card-header bg-info-subtle fw-semibold"><i class="bi bi-pin-map-fill text-secondary"></i>&nbsp;Charge Areas and Areas List</div>
                        <div class="p-2">    
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <div id="area_id" class="border rounded p-3">
                                        @foreach($charge_areas as $ca)
                                            <div class="mb-4">
                                                <h4 class="fw-semibold text-primary border-bottom pb-2">
                                                    {{ $ca->name }}
                                                </h4>
                                                <div class="row">
                                                    @foreach($areas->where('ca_id', $ca->id) as $area)
                                                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                                            <div class="form-check">
                                                                <input
                                                                    class="form-check-input border-1 border-primary"
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
