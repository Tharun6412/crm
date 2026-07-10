{{-- Delivery Manager --}}
<div class="col-sm-4">
    <label for="delivery_manager_id" class="form-label">Delivery Manager :</label>
    <select name="delivery_manager_id" id="delivery_manager_id" class="form-select">
        <option value="">Select</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}">{{ $user?->first_name }}&nbsp;{{ $user?->last_name }} - {{ $user?->emp_id }}</option>
        @endforeach
    </select>
</div>
<hr/>
<div class="card border shadow-sm mt-2">
    <div class="card-header bg-info-subtle fw-semibold"><i class="bi bi-pin-map-fill text-secondary"></i>&nbsp;Charge Areas and Areas List</div>
    <div class="p-2">
        <div class="row mb-3">
            <div class="col-sm-12">
                <div id="area_id" class="border rounded p-3">
                    <div class="col-12">
                        @if (count($charge_areas) > 0)
                        @foreach($charge_areas as $ca)
                            <div class="mb-4">
                                <h4 class="fw-semibold text-primary border-bottom pb-2">
                                    {{ $ca->name }}
                                </h4>
                                <div class="row">
                                    @foreach($areas->where('ca_id', $ca->id) as $area)
                                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input border-1 border-primary" type="checkbox" name="area_id[]" value="{{ $area->id }}"
                                                    id="area_{{ $area->id }}" @disabled(in_array($area->id, $assigned_areas))>
                                                <label class="form-check-label text-capitalize" for="area_{{ $area->id }}">{{ $area->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning" role="alert"> Select Geo Area to get Areas</div>
                    @endif
                </div>
            </div>
        </div>
    </div>    
</div>