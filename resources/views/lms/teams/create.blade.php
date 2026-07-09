{{-- Team Create Modal --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5">Add Team</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" method="POST" action="{{ url('lms/teams/store') }}">
                    @csrf
                    <div class="row mb-3">
                        {{-- Team Name --}}
                        <label for="name" class="col-sm-2 col-form-label text-end">Team Name :</label>
                        <div class="col-sm-4">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Team Name">
                        </div>
                        {{-- Department --}}
                        <label for="department_id" class="col-sm-2 col-form-label text-end">Department :</label>
                        <div class="col-sm-4">
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
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
                                    <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="responsible_user_id" class="col-sm-2 col-form-label text-end">Coordinator :</label>
                        <div class="col-sm-4">
                            <select name="responsible_user_id" id="responsible_user_id" class="form-select">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        {{-- Department --}}
                        <label for="du_id" class="col-sm-2 col-form-label text-end">Delivery Unit :</label>
                        <div class="col-sm-4">
                            <select name="du_id" id="du_id" class="form-select">
                                <option value="">Select Delivery unit</option>
                            </select>
                        </div>
                    </div>
                        
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <h4>Charge Areas and Areas :</h4>
                        </div>
                        <div class="col-sm-12">
                            <div id="area_id" class="border rounded p-3">
                                <div class="col-12">
                                    <span class="text-muted"> Select Delivery Unit to get Areas</span>
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
                    <span class="text-muted">Select Delivery Unit to get Areas</span>
                </div>
            `);
            // Get Charge Areas
            $.get("{{ url('lms/teams/gaCas') }}",{'ga_id': e.target.value},function(response){
                //for users
                let userOptions = '<option value="">Select Coordinator</option>';
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
                    response.charge_areas.forEach(function (chargeArea) {

                        html += `
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <strong>${chargeArea.name}</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                        `;
                        response.areas.forEach(function (area) {
                            const disabled = allocated.includes(area.id);
                            if (area.ca_id == chargeArea.id) {

                                html += `
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input"
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

                        html += `
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                } else {
                    html = '<span class="text-danger">No Areas Found</span>';
                }

                $('#area_id').html(html);
            });
        });
    });
</script>
