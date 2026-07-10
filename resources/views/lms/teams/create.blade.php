{{-- Team Create Modal --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
         <div class="modal-header bg-secondary-subtle">
            <h4 class="modal-title fw-semibold">Add Team</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" method="POST" action="{{ url('lms/teams/store') }}">
                    @csrf
                    <div class="row mb-3">
                        {{-- Team Name --}}
                        <div class="col-sm-4">
                            <label for="name" class="form-label fw-semibold">Team Name :</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Team Name">
                        </div>
                        {{-- Department --}}
                        <div class="col-sm-4">
                            <label for="department_id" class="form-label fw-semibold">Department :</label>
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Geo Area --}}
                        <div class="col-sm-4">
                            <label for="ga_id" class="form-label fw-semibold">Geo Area :</label>
                            <select name="ga_id" id="ga_id" class="form-select">
                                <option value="">Select Geo Area</option>
                                @foreach ($geo_areas as $ga)
                                    <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label for="responsible_user_id" class="form-label fw-semibold">Team Lead :</label>
                            <select name="responsible_user_id" id="responsible_user_id" class="form-select">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="du_id" class="form-label fw-semibold">Delivery Unit :</label>
                            <select name="du_id" id="du_id" class="form-select">
                                <option value="">Select Delivery unit</option>
                            </select>
                        </div>
                    </div>                        
                    <hr class="border-2 border-warning">
                     {{-- Charge Areas Table --}}
                    <div class="card border shadow-sm mt-2">
                        <div class="card-header bg-info-subtle fw-semibold"><i class="bi bi-pin-map-fill text-secondary"></i>&nbsp;Charge Areas and Areas List</div>                   
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <div id="area_id" class="p-2">
                                    <div class="col-12">
                                        <div class="alert alert-warning" role="alert"> Select Delivery Unit to get Areas</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    {{-- Error --}}
                    <div class="mb-3" id="team-error"></div>
                    {{-- Submit --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Save Team</button>
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
<script type="module">
    $(function(){
        $("#ga_id").on('change', function(e) {
            // Reset Areas
            $('#area_id').html(`
                <div class="col-12">
                    <div class='alert alert-warning' role='alert'>Select Delivery Unit to get Areas</div>
                </div>
            `);
            // Get Charge Areas
            $.get("{{ url('lms/teams/gaCas') }}",{'ga_id': e.target.value},function(response){
                //for users
                let userOptions = '<option value="">Select Team Lead</option>';
                if(response.users && response.users.length > 0) {
                    response.users.forEach(function(user) {
                        userOptions += `<option value="${user.id}">${user.emp_id} - ${user.name} - ${user.department?.name ?? ''}</option>`;
                    });
                }
                $('#responsible_user_id').html(userOptions);
                
            });

            // Get Delivery Units
            let dept = $('#department_id').val();
            $.get("{{ url('lms/teams/getDeliveryUnits') }}",{'ga_id': e.target.value, 'dept_id' : dept },function(response){
                //for users
                let userOptions = '<option value="">Select Delivery Unit</option>';
                if(response.delivery_units && response.delivery_units.length > 0) {
                    response.delivery_units.forEach(function(du) {
                        userOptions += `<option value="${du.id}">${du.name}</option>`;
                    });
                }
                $('#du_id').html(userOptions);
            });
        });

        $("#du_id").on('change', function () {
            $.get("{{ url('lms/teams/getDeliveryUnitAreas') }}", {
                du_id: $(this).val()
            }, function (response) {

                let html = '';

                if (response.charge_areas.length) {
                    const allocated = response.allocated_areas.map(Number);
                    html +=`<div class="row mx-1">`;
                        response.charge_areas.forEach(function (chargeArea) {
                            html += `
                                <div class="col-12 mt-3 ">
                                    <h4 class="text-primary border-bottom pb-2">${chargeArea.name}</h4>
                                </div>
                            `;
                            response.areas.forEach(function (area) {
                                const disabled = allocated.includes(area.id);
                                if (area.ca_id == chargeArea.id) {
                                    html += `
                                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input border-1 border-dark"
                                                    type="checkbox"
                                                    name="area_id[]"
                                                    value="${area.id}"
                                                    id="area_${area.id}"
                                                    ${disabled ? 'disabled' : ''}>
                                                <label class="form-check-label" for="area_${area.id}">
                                                    ${area.name}
                                                </label>
                                            </div>
                                        </div>
                                    `;
                                }
                            });
                        });
                    html += `</div>`;
                } else {
                    html = '<span class="text-danger">No Areas Found</span>';
                }

                $('#area_id').html(html);
            });
        });
    });
</script>
