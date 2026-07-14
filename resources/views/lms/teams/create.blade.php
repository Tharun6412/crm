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
                                <option value="">Select Team Lead</option>
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
                    <div id="du-areas">
                        @include('lms.teams.get-areas')
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
            // Reset Delivery Unit
            $('#du_id').html('<option value="">Select Delivery Unit</option>');
            // Reset Areas
            $('#du-areas').html(`
                <div class="card border shadow-sm mt-2">
                    <div class="card-body">
                        <div class="alert alert-warning mb-0">
                            Select Delivery Unit to get Areas
                        </div>
                    </div>
                </div>
            `);
            // Get Delivery Units
            let dept = $('#department_id').val();
            $.get("{{ url('lms/teams/getDeliveryUnits') }}",{'ga_id': e.target.value, 'dept_id' : dept },function(response){
                //for users
                let userOptions = '<option value="">Select Team Lead</option>';
                if(response.users && response.users.length > 0) {
                    response.users.forEach(function(user) {
                        userOptions += `<option value="${user.id}">${user.emp_id} - ${user.name} - ${user.department?.name ?? ''}</option>`;
                    });
                }
                $('#responsible_user_id').html(userOptions);
                // For Delivery Units
                let du_list = '<option value="">Select Delivery Unit</option>';
                if(response.delivery_units && response.delivery_units.length > 0) {
                    response.delivery_units.forEach(function(du) {
                        du_list += `<option value="${du.id}">${du.name}</option>`;
                    });
                }
                $('#du_id').html(du_list);
            });
        });

        // Get Areas By Delivery Unit ID
        $("#du_id").on('change', function () {
            let dept = $('#department_id').val();
            $.get("{{ url('lms/teams/getDeliveryUnitAreas') }}", {du_id: $(this).val(), dept_id : dept }, function (response) {
                $('#du-areas').html(response);
            });
        });
    });
</script>
