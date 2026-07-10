{{-- Team Create Modal --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-secondary-subtle">
            <h4 class="modal-title fw-semibold">Add Delivery Unit</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" method="POST" action="{{ url('lms/deliveryUnits') }}">
                    @csrf
                    <div class="row mb-3">
                        {{-- Team Name --}}
                        <div class="col-sm-4">
                            <label for="name" class="form-label">Name :</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Delivery Unit Name">
                        </div>
                        {{-- Department --}}
                        <div class="col-sm-4">
                            <label for="department_id" class="form-label">Department :</label>
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Geo Area --}}
                        <div class="col-sm-4">
                            <label for="ga_id" class="form-label">Geo Area :</label>
                            <select name="ga_id" id="ga_id" class="form-select">
                                <option value="">Select Geo Area</option>
                                @foreach ($geo_areas as $ga)
                                    <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 mt-2" id="add-user-cas">
                            @include('lms.delivery-units.get-cas')
                        </div>
                    </div>
                    {{-- Error --}}
                    <div class="mb-3" id="team-error"></div>
                    {{-- Submit --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Save</button>
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
    $(function () {
        function loadDeliveryUnitData() {
            let gaId = $('#ga_id').val();
            let departmentId = $('#department_id').val();
            // Don't call until both are selected
            if (!gaId || !departmentId) {
                return;
            }
            $.get("{{ url('lms/deliveryUnits/getCaAreas') }}", {ga_id: gaId, department_id: departmentId}, function (response) {
                $('#add-user-cas').html(response);
            });
        }
        $('#ga_id, #department_id').on('change', loadDeliveryUnitData);
    });
</script>
