{{-- Create Charge area form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Charge Area</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="loc-area-create-success">
                <form action="{{ url('master/location/areas') }}" method="POST" id="loc-area-create-form">
                    @csrf
                    <div class="row mb-2">
                        <label for="ga_id" class="col-sm-2 col-form-label text-end">Geo Area</label>
                        <div class="col-sm-9">
                            <select name="ga_id" id="ga_id" class="form-select">
                                <option value="">Select Geo Area</option>
                                @foreach ($geo_areas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="district_id" class="col-sm-2 col-form-label text-end">District</label>
                        <div class="col-sm-9">
                            <select name="district_id" id="district_id" class="form-select">
                                <option value="">Select District</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="ca_id" class="col-sm-2 col-form-label text-end">Charge Area</label>
                        <div class="col-sm-9">
                            <select name="ca_id" id="ca_id" class="form-select">
                                <option value="">Select Chareg Area</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="name" class="col-sm-2 col-form-label text-end">Area Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                    </div>
                    <div id="loc-area-create-error" class="text-danger"></div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-9">
                            <button type="submit" class="btn btn-success"><i class="bi bi-plus-lg"></i>&nbsp;Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
{{-- Scripts --}}
@include('scripts.ajax-form-submit', ['form' => 'loc-area-create'])
<script type="module">
    $(function(){
        // Choose GA for districts
        $("#ga_id").on('change', function(e) {
            $.get("{{ url('common/gaDistricts') }}", {'ga_id': e.target.value}, function(response){
                let options = '<option value = "">Select district</option>';
                if(response.districts && response.districts.length > 0) {
                    response.districts.forEach(function(dist) {
                        options += `<option value="${dist.id}">${dist.name}</option>`;
                    });
                }
                $('#district_id').html(options);
            })
        });
        // Choose Districts for charge areas
        $("#district_id").on('change', function(e) {
            $.get("{{ url('common/districtCas') }}", {'district_id': e.target.value}, function(response){
                let options = '<option value = "">Select Charge Area</option>';
                if(response.charge_areas && response.charge_areas.length > 0) {
                    response.charge_areas.forEach(function(dist) {
                        options += `<option value="${dist.id}">${dist.name}</option>`;
                    });
                }
                $('#ca_id').html(options);
            })
        });
    });
</script>