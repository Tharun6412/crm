{{-- Team Create Modal --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5">Add Delivery Unit</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="team-success">
                <form id="team-form" method="POST" action="{{ url('lms/deliveryUnits') }}">
                    @csrf
                    <div class="row mb-3">
                        {{-- Team Name --}}
                        <label for="name" class="col-sm-2 col-form-label text-end">Name :</label>
                        <div class="col-sm-2">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Delivery Unit Name">
                        </div>
                        {{-- Department --}}
                        <label for="department_id" class="col-sm-2 col-form-label text-end">Department :</label>
                        <div class="col-sm-2">
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Geo Area --}}
                        <label for="ga_id" class="col-sm-2 col-form-label text-end">Geo Area :</label>
                        <div class="col-sm-2">
                            <select name="ga_id" id="ga_id" class="form-select">
                                <option value="">Select Geo Area</option>
                                @foreach ($geo_areas as $ga)
                                    <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Charge Area and User --}}
                    <div class="row mb-3" id="charge_area">
                        @include('lms.delivery-units.get-cas')
                    </div>
                    <hr>
                    {{-- Charge Areas --}}
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <h4>Charge Areas and Areas :</h4>
                        </div>
                        <div class="col-sm-12">
                            <div id="area_id" class="border rounded p-3">
                                <div class="col-12">
                                    <span class="text-muted"> Select Charge Area to get Areas</span>
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
<script type="text/javascript">
    $(function(){
        // Get Charge areas
        $("#ga_id").on('change', function(e) {
            $.get("{{ url('lms/deliveryUnits/gaCas') }}",{'ga_id': e.target.value},function(response){
                $('#charge_area').html(response);
            });
        });
    });
</script>
